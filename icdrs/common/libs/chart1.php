<?php
// Load config.
require_once dirname(__DIR__, 2)."/config/localconfig.php";

require_once FullCommonPath."/amchart/chart/cachechart.php";
require_once FullCommonPath."/amchart/chart/charthelperfunctions.php";
require_once dirname(__DIR__, 2)."/config/chartaveragetype.php";
require_once dirname(__DIR__, 2)."/config/chartaveragetypeconfig.php";

/**
 * TotalChart has this behave:
 *  - GraphDatasetType is grapable, 
 *    That mean is data must be not available in whole period.
 *  
 *  - GraphType is Static,
 *    That mean is entity in this chart is static and defined by implemention in this class.
 * 
 * All of description of methods is available in Chart interface.
 * 
 */
class Chart{
    /**
     * To hold final chartName and make file name of cache file.
     */
    public $chartName = null;

    /**
     * A name for chart that used for show chart title.
     */
    public $chartTitle = null;

    /**
     * A config for Height of lengend for chart.
     */
    public $chartLengendHeightPerPixel = null;
    /**
     * A config for Height of box for chart.
     */
    public $chartBoxHeightPerPixel = null;

    /**
     * In graph for none-persent data states, show a gap or show continued graph line.
     */
    public $graphType = null;

    /**
     * SQL query that used get data from database to make cache.
     * 
     * result of this query is an array with bellow structure.
     * [dateTime, errorCount]
     */
    protected  $query = null;

    /**
     * Average must be set in drived class. 
     */
    protected $averageList = [];

    /**
     * Crop each query to a N minute.
     */
    protected $bufferMinuteCountToFetch = null;

    /**
     * A period based minute to show a time for build cache in first try.
     */
    protected $periodForFirstBuildCacheBasedMinute = null;

    /**
     * Each cache line stored with a fetch time that mean is that each
     * cache line have a time to show data age.
     * This property show how much valid age for cache lines.
     * cache lines with not enough age refetch. 
     */
    public $refreshLastPointBasedMinute = null;
    

    /**
     * In case that not anything found to build cache.
     * This property show wait time for new fetch.
     */
    protected $waitForFetchAgainBasedSecond = null;

    /**
     * For Charts that contain analytic feature.
     * This propety must be set as 'coefficient' between 0.1 to 2.0 or any value
     * To remove deviated numbers in calculation of inc/dec rate.
     */
    public $deleteCoefficient = null;
    
    /**
     * set a timeshift for chart based minute.
     * for add a delay in 2 min. set it 2.
     */
    public $chartMinuteShift = null;

    /**
     * Defined entities set in drived class.
     */
    public $entities = [];

    /**
     * Parameters in SQL query.
     */
    private $_param = [];

    /**
     * Reset params of SQL query.
     */
    private function resetQueryParam(){
        $this->_param = [];
    }


    /**
     * 
     */
    private function cropTimePeriod($startTS, $endTS){
        $result = [];
        for ($i = $startTS; $i <= $endTS; $i += ($this->bufferMinuteCountToFetch * 60)) {
            // Find start timestamp in a crop.
            $sTS = $i;

            // Find end timestamp in a crop.
            $eTS = $i + $this->bufferMinuteCountToFetch * 60 - 60;
            // IN case that a crop end timestamp, it's main end timestamp.
            if ($eTS > $endTS) {
                $eTS = $endTS;
            }

            $result[] = [$sTS, $eTS];
        }
        $resultCount = count($result)-1;
        ChartHelperFunctions::logPrinter($this->chartName." Croped to $resultCount period; Each crop period is $this->bufferMinuteCountToFetch minute in config.");

        return $result;
    }

    /**
     * 
     */
    public function buildCache(){
        // Detect that SUB_QUERY_MODE is on. 
        $SUB_QUERY_MODE = false;
        if($this->query == ''){
            $SUB_QUERY_MODE = true;
            // Get all defined properties in drived class and validate.
            $classVariables = get_object_vars($this);
            // Validate __sub__function define.
            if(!isset($classVariables["__sub__function"])){
                echo "An error happen in $this->chartName, __sub__function not implemented! \n";
                exit;
            }
            if(! $classVariables['__sub__function'] instanceof Closure){
                echo "An error happen in $this->chartName, __sub__function not instanceof Closure! \n";
                exit;
            }
            
            // Validate __sub__prepair array.
            if(!array_key_exists("__sub__prepair", $classVariables)){
                echo "An error happen in $this->chartName, __sub__prepair not implemented! \n";
                exit;
            }
            if(!is_array($classVariables['__sub__prepair'])){
                echo "An error happen in $this->chartName, __sub__prepair not an array! \n";
                exit;
            }
            if(count($classVariables['__sub__prepair']) == 0){
                echo "An error happen in $this->chartName, __sub__prepair can not be empty! \n";
                exit;
            }

            // Get sub queries that defined.
            /**
             * This list store all sub queries variable names.
             * eg: ['__sub__total', '__sub__error']
             */
            $this->subQueryList = [];
            
            /**
             * Store all sub queries result variable names.
             * eg: ['__total', '__error']
             */
            $this->subQueryResultList = [];
            
            // Do stuff a validate on all sub queries. 
            foreach($classVariables['__sub__prepair'] as $e){
                $subQueryName = "__sub__".$e;
                if(!key_exists($subQueryName, $classVariables)){
                    echo "An error happen in $this->chartName, $subQueryName defined but not implemented ! \n";
                    exit;                        
                }
                $this->subQueryList[] = $this->$subQueryName;
                $this->subQueryResultList[] = explode('__sub', $subQueryName)[1];
            }
        }
        
        // Make Directory for cache if not exist.
        $cacheDirPath = dirname(__DIR__, 2)."/cachepool/".$this->chartName."/";
        if (!file_exists($cacheDirPath)) {
            mkdir($cacheDirPath, 0777);

            ChartHelperFunctions::logPrinter($this->chartName." Make cache directory.");
        }
        
        $startMainTS = null;
        $endMainTS = null;
        // In case that nothing found to make cache, check again with a specific interval.
        while(true){
            // Get the main period time to build cache.
            $mainTS = ChartHelperFunctions::getMainPeriodTimestamp(
                $this->chartName, $this->refreshLastPointBasedMinute, $this->periodForFirstBuildCacheBasedMinute
            );
            $startMainTS = $mainTS->startMainTS;
            $endMainTS = $mainTS->endMainTS;

            if($startMainTS > $endMainTS){
                ChartHelperFunctions::logPrinter($this->chartName." Nothing to build cache, try in next $this->waitForFetchAgainBasedSecond second latter.");
                sleep($this->waitForFetchAgainBasedSecond);
            }else{
                break;
            }
        }
        
        // Main period timestamp is ok to build cache.
        $endMainDatetime = ChartHelperFunctions::getDatetimeByTimestamp($endMainTS);
        $startMainDatetime = ChartHelperFunctions::getDatetimeByTimestamp($startMainTS);
        ChartHelperFunctions::logPrinter($this->chartName." Main period for build cache $startMainDatetime to $endMainDatetime.");

        

        // Crop main period to N smaller period.
        $cropIndex = 0;
        $cropTimeList = $this->cropTimePeriod($startMainTS, $endMainTS);
        $cropCount = count($cropTimeList)-1;
        foreach($cropTimeList as $cropTime){
            // For each fetch from database reset SQL query params.
            $this->resetQueryParam();
            
            // Get period for current crop and init them in params. 
            $startTS = $cropTime[0];
            $endTS = $cropTime[1];
            $startDatetime = ChartHelperFunctions::getDatetimeByTimestamp($startTS);
            $endDatetime = ChartHelperFunctions::getDatetimeByTimestamp($endTS);
            $this->_param['startTime'] = $startDatetime;
            $this->_param['endTime'] = $endDatetime;
            

            ChartHelperFunctions::logPrinter($this->chartName." Start for Crop; $cropIndex/$cropCount, for period $startDatetime to $endDatetime.");

            /**
             * Hold all entity result.
             * This variable have a list of array of each result of each entity.
             */
            $resultInCrop = [];
            
            /**
             * In each crop, fetch time for last entity fetch.
             * It's used to show fetch time for all entity in a crop.
             */ 
            $fetchTimestamp = 0;            
            $entitiesCount = count($this->entities)-1;
            // Iterate on each entity.
            ChartHelperFunctions::logPrinter($this->chartName." Start fetch $entitiesCount entities from database.");
            $entityIndex = 0;
            foreach($this->entities as $entity){
                // Allocated params to make a executable query.
                foreach($entity["param"] as $key => $value){
                    $this->_param[$key] = $value;
                }
                $entityLabel = $entity['label'];

                // It store result of query.
                $result = null;

                // Check for detect SUB_QUERY_MODE. 
                if($SUB_QUERY_MODE){
                    // Iterate on all sub queries.
                    // Run each one of them and store result in related variable.
                    for($i=0; $i<count($this->subQueryList); $i++){
                        $_queryMaked = ChartHelperFunctions::queryMaker($this->subQueryList[$i], $this->_param);
                        $_queryResult = $this->subQueryResultList[$i];
                        $this->$_queryResult = query($_queryMaked);
                    }
                    // Run __sub__function method to calculate final data from sub queries result.
                    $result = $classVariables['__sub__function']();
                }else{
                    // Run maked query on database.
                    $queryMaked = ChartHelperFunctions::queryMaker($this->query, $this->_param);
                    $result = query($queryMaked)->result;
                }

                ChartHelperFunctions::logPrinter($this->chartName." Entity $entityLabel fetched; status $entityIndex/$entitiesCount.");
                // Get fetch time.
                $fetchTimestamp = time();

                // Repair result that fetched from database.
                $result = ChartHelperFunctions::repair($result, $startTS, $endTS);
                
                // Add repaired result in list.
                $resultInEntity = [
                    'label'=>$entityLabel,
                    'result'=>$result,
                ];
                $resultInCrop[] = $resultInEntity;
                $entityIndex++;
            }

            // Make files for this crop time.

            /**
             * An array that hold cache files address.
             * This array is a list of array that key defined in this approach:
             * -Ymd example -> -20220212 a sample of key in array.
             */
            $fileFullPathList = [];
            
            /**
             * AT finally all of data that collected for entities.
             * breaked to for each day and store to bellow variable.
             */
            $collectedCacheLines = [];

            /**
             * Calculate a range to show cache files for current crop.
             */
            // Get day number in current year for start and end period. 
            $startDayIndex = date("z", $startTS);
            $endDayIndex = date("z", $endTS);
            // Set a range from 0 to N.
            $endDayIndex = $endDayIndex - $startDayIndex;
            $startDayIndex = 0;

            // Iterate on range.
            for($i=$startDayIndex; $i<=$endDayIndex;$i++){
                // Make cache file name.
                $fileNameTS = $startTS+($i*24*60*60);
                $fileNamePureTS = strtotime(date('Y-m-d 00:00:00', $fileNameTS));
                $fileName_Ymd_FormatDatetime = ChartHelperFunctions::getDate_Ymd_ByTimestamp($fileNameTS);
                $fileNameEndSection = $fileName_Ymd_FormatDatetime.".cbsc";
                // Make cache file address.
                $fileFullPath = $cacheDirPath.$this->chartName."-".$fileNameEndSection;

                ChartHelperFunctions::logPrinter($this->chartName." The $fileNameEndSection cahe file updated/created in current execution.");
                
                // In case that cache file not exist,
                // Make it.
                // Indexing it.
                if(!file_exists($fileFullPath)){
                    ChartHelperFunctions::logPrinter($this->chartName." Make $fileNameEndSection cahe file.");
                    $cbscFileHandler = fopen($fileFullPath, "w");
                    for($j=0; $j<(24*60*60); $j +=60){
                        $fileLineIndex = ChartHelperFunctions::getDatetimeByTimestamp($fileNamePureTS+$j);
                        $_line = strtotime($fileLineIndex).",";
                        
                        // Add dateTime in Dev area.
                        if(CACHE_FILE_TYPE == 'Dev'){
                            $_line .= $fileLineIndex.",";
                        }
                        fwrite($cbscFileHandler, $_line."\n");
                    }
                    fclose($cbscFileHandler);
                }
                /**
                 * Add cache file address to a list.
                 * More detail about key writed in defination of variable section.
                 */
                $fileFullPathList[$fileNameEndSection] = $fileFullPath;
                $collectedCacheLines[$fileNameEndSection] = [];
            }

            // Iterate per minute.
            for($currentTS = $startTS, $i=0; $currentTS<=$endTS; $currentTS+=60, $i++){
                // Make line to import to cache.
                $FileIndex = $currentTS.","; 
                $FileIndexDatetime = '';
                $FetchTimestamp = $fetchTimestamp.",";
                $FetchDatetime = '';

                // Add dateTime in Dev area.
                if(CACHE_FILE_TYPE == 'Dev'){
                    $FileIndexDatetime = ChartHelperFunctions::getDatetimeByTimestamp($currentTS).",";
                    $FetchDatetime = date('Y-m-d H:i:00', $fetchTimestamp).",";
                }
                $line = $FileIndex.$FileIndexDatetime.$FetchTimestamp.$FetchDatetime;
                
                // Iterate on each entity.
                foreach($resultInCrop as $resultInEntity){
                    $entityLabel = $resultInEntity['label'];
                    $entityResult = $resultInEntity['result'][$i]['errorCount'];
                    $line .= $entityLabel.":".$entityResult.",";
                }
                $line = rtrim($line, ',');


                // Calculate Key array to store lines in related CollectedCacheLines.
                $fileNameEndSection = ChartHelperFunctions::getDate_Ymd_ByTimestamp($currentTS).".cbsc";
                $collectedCacheLines[$fileNameEndSection][] = $line;
            }

            // Iterate on cahe files to store collectedCacheLine in cache files.
            foreach($fileFullPathList as $fileNameEndSection => $fileFullPath){

                $firstLine = $collectedCacheLines[$fileNameEndSection][0];

                $firstLineFileIndexTimestamp = explode(",", $firstLine)[0];

                $lastLineIndex = count($collectedCacheLines[$fileNameEndSection])-1;
                $lastLine = $collectedCacheLines[$fileNameEndSection][$lastLineIndex];
                
                $lastLineFileIndexTimestamp = explode(',', $lastLine)[0];

                // Find first and last fileIndex related with collected data. 
                $firstLineFileIndex = ChartHelperFunctions::getPassedMinuteInDayFromTimestamp($firstLineFileIndexTimestamp);
                $lastLineFileIndex = ChartHelperFunctions::getPassedMinuteInDayFromTimestamp($lastLineFileIndexTimestamp);

                // Open current cache file as an array to change specificated line.
                $fileLineList = file($fileFullPath);
                for($i=0, $currentLineFileIndex = $firstLineFileIndex; $currentLineFileIndex<=$lastLineFileIndex; $i++, $currentLineFileIndex++){
                    $fileLineList[$currentLineFileIndex] = $collectedCacheLines[$fileNameEndSection][$i]."\n";

                }
                // Save change in cache file.
                file_put_contents($fileFullPath, implode('', $fileLineList));

                ChartHelperFunctions::logPrinter($this->chartName." From FileIndex $firstLineFileIndex to $lastLineFileIndex in cache file $fileNameEndSection updated.");
            }
            ChartHelperFunctions::logPrinter($this->chartName." End for Crop $cropIndex/$cropCount.");
            $cropIndex++;
        }
    }
}

class GraphType{
    const Gap = 'gap';
    const Continued = 'continued';
}