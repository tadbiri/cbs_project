<?php
class TrafficAnalytic{

    // Attributes that configured in init class.
    public $chartName = null;

    // Current point value, key/value array, key is entityName.
    public $currentPoints = [];

    // Current timestamp to fetch.
    public $currentTS = null;

    /**
     * Cycle defined in here.
     * In here set a cycle for calculation.
     */ 
    public $cycleDay = 7;

    /**
     * Hold result of data that collected from cache.
     */
    public $cacheResult = null;

    /**
     * Initial Variance builder.
     * 
     * @param string $chartName 
     * 
     * @return void.
     */
    public function __construct($chartName){
        // Check that chart API file is exist.
        $chartPath = dirname(__DIR__, 2)."/api/".$chartName.".php";
        if(!file_exists($chartPath)){
            echo "Error: $chartName not found to build Variance! \n";
            exit;
        }

        // Set params.
        $this->chartName = $chartName;
    }

    /**
     * Get raw result from cache files. 
     *  
     * @return object {
     *  
     *  fileLineIndex int, for show line file index that related to intered timestamp.
     * 
     *  entityList key/value array, [entityName]=> value segment is array and hold data, each of item 
     *      At this array related to a date.
     *  
     *  cacheFiles array, hold all cache files.
     * 
     *  matchedFiles array, hold matched files.
     * 
     *  expectedFiles array, hold expected files.
     *  
     * }
     */
    public function getCacheResult(){
        $result = new stdClass();

        /**
         * Detect line index.
         * For all file caches line index is fixed.
         */
        $result->fileLineIndex = CacheChart::getFileLineIndexByTimestamp($this->currentTS);

        /**
         * Include defined class and get instance of it.
         */
        $chartName = $this->chartName;
        $chartPath = dirname(__DIR__, 2)."/api/".$chartName.".php";
        require_once $chartPath;
        $chartObject = new $chartName();

        /**
         * Get all cache files that belong this chart.
         * Sort them DESC on date attribute
         * And make a related list that just hold date of file in pure timestamp format.
         * Pure timestamp that mean is timestamp that belong to 00:00:00 of this date.
         */
        $result->cacheFiles = CacheChart::getCacheFileList(strtolower($chartName));
        $result->cacheFiles = array_reverse($result->cacheFiles);
        $cacheFilesTS = array_map(function($file){
            return CacheChart::getPureTimestampFromFileName($file);
        }, $result->cacheFiles);

        /**
         * Find all matched file.
         * In each step find point based cycle and do this
         * Operation to end of file list.
         */
        $result->matchedFiles = [];
        $currentTSPure = strtotime(ChartHelperFunctions::getStartDayDateTime($this->currentTS));
        $_cycleSecond = $this->cycleDay*24*3600;
        $cacheFilesCount = count($result->cacheFiles);
        // Hold expected cache files,
        $result->expectedFiles = [];
        for($i=0; $i<$cacheFilesCount; $i+=$this->cycleDay){
            // Find files based cycle config.
            $currentTSPure = $currentTSPure-($_cycleSecond);

            // Break last in-valid file.
            if($cacheFilesTS[$cacheFilesCount-1] > $currentTSPure){
                break;
            }
            // Make expected cache file name.
            $expectedFileName = strtolower($this->chartName)."-".ChartHelperFunctions::getDate_Ymd_ByTimestamp($currentTSPure).".cbsc";
            $result->expectedFiles[] = $expectedFileName;

            // Ignore that expected file not found.
            $_index = array_search($currentTSPure, $cacheFilesTS);
            if(!is_numeric($_index)){
                continue;
            }

            // In case expected file exist, add it to mathed list.
            $result->matchedFiles[] = $expectedFileName;
        }

        // Make entity list.
        $result->entityList = [];
        foreach($result->matchedFiles as $fileName){

            // Get file lines.
            $fileLines = file(CacheChart::getFullPath($fileName));

            // Get line of file by line index.
            $fileLine = $fileLines[$result->fileLineIndex];
            $entityArray = array_slice(explode(",", $fileLine), 
                ENTITY_START_INDEX, 
                count($chartObject->entities)
            );

            // Remove space and order useless chart in entity.
            $entityArray = array_map(function($_entity){
                return trim($_entity);
            }, $entityArray);

            // Add fetch values from files.
            foreach($chartObject->entities as $i => $entity){
                $entityKey = $chartObject->entities[$i]['label'];
                /**
                 * Check that data existed or not.
                 * in case that not any data found add 'null' string.
                 */
                if(count($entityArray) == 0 || strlen($entityArray[0]) == 0){
                    $result->entityList[$entityKey][] = 'null';
                }else{
                    $entity = $entityArray[$i];
                    $entityValue = (int) ENTITY_KEY_IS_EXIST? explode(':', $entity)[1]: $entity;
                    $result->entityList[$entityKey][] = $entityValue;
                }
            }
        }

        // Set result of this method in class.
        $this->cacheResult = $result;

        return $result;
    }

    /**
     * Main fucntion to do analytic operation.
     * This method calculate increase/decrease rent for all current point of chart entity.
     * 
     * @param int $currentTS int current timestamp for fetch from cache files.
     * 
     * 
     * 
     */
    public function getCurrentPercent($currentTS){
        $result = [];

        // Set attributes.
        $this->currentTS = $currentTS;

        // Get result to start calculation.
        $this->getCacheResult();

        // Get clean entities, in this step all NaN and null removed from lists.
        $cleanedEntityList = $this->cleanEntityList($this->cacheResult->entityList);

        foreach($this->cacheResult->expectedFiles as $expectedFileNameList){
            if(!in_array($expectedFileNameList, $this->cacheResult->matchedFiles)){
                $_currentDatetime = date('Y-m-d H:i:s', $currentTS);
                $_fileLineIndex = $this->cacheResult->fileLineIndex;
                echo "ERRROR: In analytic operation for point '$_currentDatetime' (fileLineIndex: $_fileLineIndex), 
                      file '$expectedFileNameList' expected but not found. \n";
            }
        }

        // Iterate on entities.
        foreach($cleanedEntityList as $entityName => $list){

            $res = new stdClass();

            $res->entityName = $entityName;
            // Clear entities by standard devision method.
            $res->standardDeviation = $this->getStandardDeviationOfList($list);;
            $res->list = $list;

            $result[] = $res;
        }
        return $result;
    }



    /** Helper */
    /**
     * Get entity list and remove invalid data.
     * Such as 'NaN' and 'null' strings that 
     * Consider as invalid data.
     * 
     * @param array $entityList
     * 
     * @return array
     */
    public function cleanEntityList($entityList){
        $_result = [];
        foreach($entityList as $entityName => $list){
            $_counter = 0;
            $_result[$entityName] = [];
            foreach($list as $value){
                if($value == 'NaN' || $value == 'null'){
                    $_counter++;
                    continue;
                }


                $_cacheFileTS = CacheChart::getPureTimestampFromFileName($this->cacheResult->matchedFiles[$_counter]);
                $_result[$entityName][] = [$_cacheFileTS, $value];
                $_counter++;
            }
        }

        return $_result;
    }

     /**
     * Get a list as an array and calculate variance object
     * 
     * @param array $list a list that contain numbers.
     * 
     * @return int standdardDeviation float 2 point,
    */
    public function getStandardDeviationOfList($list){
        $listCount = count($list);
        if($listCount == 0 || $listCount == 1){
            return 0;
        }
        // Get average.
        $pureList = array_map(function($item){
            return $item[1];
        }, $list);
        $average = (float) array_sum($pureList)/$listCount;

        // Calculate the square of difference from number, for each entity.
        $_listSquare = [];
        foreach($pureList as $e){
            $_square = pow($e-$average, 2);
            $_listSquare[] = $_square;
        }

        // Get average from square list.
        // It's called Variance.
        $variance = array_sum($_listSquare)/$listCount; 

        // Calculate Standard Deviation.
        $standardDeviation =  sqrt($variance);
        $standardDeviation = round($standardDeviation, 2, PHP_ROUND_HALF_UP);

        return $standardDeviation;
    }

}