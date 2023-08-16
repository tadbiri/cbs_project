<?php
class CacheChart{
    /**
     * Get full path of file.
     * 
     * @param string $fileName fileName.
     * 
     * @return string file full path.
     */
    public static function getFullPath($fileName){
        $chart = explode('-', $fileName)[0];
        return CACHE_POOL_DIR.$chart.'/'.$fileName;
    }

    /**
     * Get a list of cache file names by chartName and etc params.
     * To use date filter set both of them.
     * To set a date param as a infinal param set it null.
     * 
     * @param string $chartName
     * @param string $startDate, for ignore use null. 
     * @param string $endDate, for ignore use null
     */
    public static function getCacheFileList($chartName, $startDate = null, $endDate = null){    
        /**
         * Get dir file list.
         */
        $chartCacheDirectory = CACHE_POOL_DIR.$chartName."/";
        if (!file_exists($chartCacheDirectory)){
            return [];
        }
        $fileList = array_diff(scandir($chartCacheDirectory), array('.', '..'));
        $fileList = array_values($fileList);
    
        /**
         * Filter just .cbsc files.
         */
        $fileListCount = count($fileList);
        for($i=0; $i<$fileListCount; $i++){
            if(!strstr($fileList[$i], '.cbsc')){
                unset($fileList[$i]);
            }
        }
        $fileList = array_values($fileList);
        

        /**
         * Filter by start date param.
         */
        if($startDate != null){
            $startDate = strtotime($startDate.' 00:00:00');
            $fileListCount = count($fileList);
            for($i=0; $i<$fileListCount; $i++){
                $fileDatePostfix = explode("-", $fileList[$i])[1];
                $fileDate = explode('.', $fileDatePostfix)[0];
                $fileDatetime = substr($fileDate, 0, 4).'-'.substr($fileDate, 4, 2).'-'.substr($fileDate, 6, 2).' 00:00:00';
                $fileTimestamp = strtotime($fileDatetime);
                if($fileTimestamp < $startDate){
                    unset($fileList[$i]);
                }
            }
            $fileList = array_values($fileList);
        }

        /**
         * Filter by end date param.
         */
        if($endDate != null){
            $endDate = strtotime($endDate.' 00:00:00');
            $fileListCount = count($fileList);
            for($i=0; $i<$fileListCount; $i++){
                $fileDatePostfix = explode("-", $fileList[$i])[1];
                $fileDate = explode('.', $fileDatePostfix)[0];
                $fileDatetime = substr($fileDate, 0, 4).'-'.substr($fileDate, 4, 2).'-'.substr($fileDate, 6, 2).' 00:00:00';
                $fileTimestamp = strtotime($fileDatetime);
                if($fileTimestamp > $endDate){
                    unset($fileList[$i]);
                }
            }
            $fileList = array_values($fileList);
        }

        // Sort a-z files.
        $fileList = self::sortFileListByDateASC($fileList);

        return $fileList;
    }

    /**
     * Sort fileList.
     * 
     * @param array $fileList
     * 
     * @return array
     */
    private static function sortFileListByDateASC($fileList){
        $result = [];


        $fileTimestampList = [];
        foreach($fileList as $file){
            $fileDatePostfix = explode("-", $file)[1];
            $fileDate = explode('.', $fileDatePostfix)[0];
            $fileDatetime = substr($fileDate, 0, 4).'-'.substr($fileDate, 4, 2).'-'.substr($fileDate, 6, 2).' 00:00:00';
            $fileTimestamp = strtotime($fileDatetime);
            $fileTimestampList[] = $fileTimestamp; 
        }
        sort($fileTimestampList);
        foreach($fileTimestampList as $ts){
            foreach($fileList as $file){
                $fileDatePostfix = explode("-", $file)[1];
                $fileDate = explode('.', $fileDatePostfix)[0];
                $fileDatetime = substr($fileDate, 0, 4).'-'.substr($fileDate, 4, 2).'-'.substr($fileDate, 6, 2).' 00:00:00';
                $fileTimestamp = strtotime($fileDatetime);

                if($ts == $fileTimestamp){
                    $result[] = $file;
                    continue;
                }
            }
        }

        return $result;
    }

    /**
     * Get two index of file, start and end index for exsited data.
     * 
     * @param strnig $fileName
     */
    public static function getLineIndexes($fileName){
        $result = new stdClass();
        $result->startFileIndex = -1;
        $result->endFileIndex = -1;
        $fileLines = file(self::getFullPath($fileName));
        // Ignore empty files.
        if (empty($fileLines)) {
            return $result;
        }
        // Iterate on lines.
        for ($i = 0; $i < count($fileLines); $i++) {
            // Convert to array current line.
            $fileLine = $fileLines[$i];
            $fileLineObject = explode(',', $fileLine);
            
            // Break in error exist in line.
            if(count($fileLineObject) < (EMPTY_LINE_COMMA_COUNT+1)){
                $result->startFileIndex = -1;
                $result->endFileIndex = -1;
                break;
            }

            // Check that line is empty.
            if (count($fileLineObject) == (EMPTY_LINE_COMMA_COUNT + 1)) {
                if ($result->startFileIndex == -1) {
                    continue;
                }
                $result->endFileIndex = $i - 1;
                break;
            }
            if ($result->startFileIndex == -1) {
                $result->startFileIndex = $i;
            }
            $result->endFileIndex = $i;
        }
        return $result;
    }

        /**
     * Get two index of file, start and end index for exsited data.
     * 
     * @param strnig $fileName
     */
    public static function getLineIndexesByFilePath($filePath){
        $result = new stdClass();
        $result->startFileIndex = -1;
        $result->endFileIndex = -1;
        $fileLines = file($filePath);
        // Ignore empty files.
        if (empty($fileLines)) {
            return $result;
        }
        // Iterate on lines.
        for ($i = 0; $i < count($fileLines); $i++) {
            // Convert to array current line.
            $fileLine = $fileLines[$i];
            $fileLineObject = explode(',', $fileLine);

            // Break in error exist in line.
            if(count($fileLineObject) < (EMPTY_LINE_COMMA_COUNT+1)){
                $result->startFileIndex = -1;
                $result->endFileIndex = -1;
                break;
            }

            // Check that line is empty.
            if (count($fileLineObject) == (EMPTY_LINE_COMMA_COUNT + 1)) {
                if ($result->startFileIndex == -1) {
                    continue;
                }
                $result->endFileIndex = $i - 1;
                break;
            }
            if ($result->startFileIndex == -1) {
                $result->startFileIndex = $i;
            }
            $result->endFileIndex = $i;
        }
        return $result;
    }

    /**
     * Get a timestamp and return file line index.
     * 
     * @param int $timestamp
     * 
     * @return int fileLineIndex
     */
    public static function getFileLineIndexByTimestamp($timestamp){
        $hourMinute = explode(':', date('H:i', ($timestamp)));
        $hour = (int) $hourMinute[0];
        $minute = (int) $hourMinute[1];
        $fileLineIndex = ($hour*60)+$minute;
        return $fileLineIndex;
    }

    /**
     * Get file name and return pure TS of file.
     * 
     * @param string $filename
     * 
     * @return int pure timestamp
     */
    public static function getPureTimestampFromFileName($fileName){
        $_date = explode('.', explode('-', $fileName)[1])[0];
        $_dateTime = sprintf("%s-%s-%s 00:00:00",
            substr($_date, 0, 4),
            substr($_date, 4, 2),
            substr($_date, 6, 2)
        );
        return strtotime($_dateTime);
    }
    /**
     * Get start time to build cache based cache file.
     * 
     * @param array $fileList a list of files.
     * @param int $RefreshLastPointBasedMinute
     * 
     * @return array lineObject
     */
    public static function findStartTimeToRebuildCache($fileList, $RefreshLastPointBasedMinute){

        /**
         * Convert to second.
         * It'a a config in chartAverageType that show time-length of validity data in cache.
         * 
         * For example if this time is 2 min, that mean is that all data in this averageType valid,
         * When spend more than 2 min from fetch time.
         */
        $RefreshLastPoint = $RefreshLastPointBasedMinute*60;
        
        /**
         * To detect gap between data.
         * If any data found, gap must be considered.
         */
        $sensitiveToFindGap = false;

        // To hold last line of last file.
        $lastLineOfLastFile = null;

        // Iterate on file in order ASC.
        foreach($fileList as $file){
            $fileHandler = fopen(self::getFullPath($file), "r");
            // Iterate on to reach EOF.
            while(!feof($fileHandler)) {
                $fileLine = fgets($fileHandler);
                if($fileLine == ''){
                    break;
                }
                // Convert line to array.
                $fileLineObject = explode(',', $fileLine);  
                $lastLineOfLastFile = $fileLineObject;  
                // Ignore lines that empty.
                if(count($fileLineObject) == (EMPTY_LINE_COMMA_COUNT+1)){
                    // After detect a complate line, wait for gap.
                    if($sensitiveToFindGap){
                        return $fileLineObject[FILEINDEX_TIMESTAMP_INDEX]; 
                    }
                    continue;
                }
                // Be true for gap falg.
                $sensitiveToFindGap = true;

                // Calculate age to decide data time-life.
                $dataAgeSecond = $fileLineObject[FETCH_TIMESTAMP_INDEX]-$fileLineObject[FILEINDEX_TIMESTAMP_INDEX];
                if($dataAgeSecond > $RefreshLastPoint){
                    continue;
                }       
                // return fileIndex from line that is no have enough time-length.
                fclose($fileHandler);
                return $fileLineObject[FILEINDEX_TIMESTAMP_INDEX];
            }
            fclose($fileHandler);
        }
        
        /**
         * In case that not any valid date found to start index,
         * Just use last line to find next index.
         */
        return $lastLineOfLastFile[FILEINDEX_TIMESTAMP_INDEX]+60;
    }

        /**
     * Get entity from cache files, in case that not any thing found 
     * In files array will be empty.
     * 
     * @param string $chartname
     * 
     * @return array entities
     */
    public static function fetchEntityFromCacheFiles($chart_name){

        // Get all cache files.
        $files = self::getCacheFileList($chart_name);
        $entity = [];

        foreach($files as $file){

            /**
             * Iterate on files and find a file that have entities.
             * Then fetch them.
             */
            $path = CACHE_POOL_DIR.strtolower($chart_name)."/".$file;
            $fileIndexs = self::getLineIndexesByFilePath($path);
            if($fileIndexs->startFileIndex != -1){
                $fileLines = file($path);
                $lineSplited = explode(',', $fileLines[$fileIndexs->startFileIndex]);
                $entities = array_slice($lineSplited, ENTITY_START_INDEX);
                foreach($entities as $e){
                    $entity[] = explode(":", $e)[0];
                }
                break;
            }
        }
        return $entity;
    }
}



