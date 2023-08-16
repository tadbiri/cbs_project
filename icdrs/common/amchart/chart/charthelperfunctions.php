<?php
class ChartHelperFunctions{
    /**
     * Make a executable query to run on database.
     * 
     * @param string $query eg: SELECT * FROM :db_name:
     * @param array $param eg: ['db_name'=>'customer']
     * 
     * @return strning eg: SELECT * FROM customer
     */
    public static function queryMaker($query, $param){
        foreach($param as $key => $value){
            $query = str_replace(":".$key.":", $value, $query);
        }
        return $query;
    }
    
    
    /**
     * get start day dateTime.
     * 
     * @param int $timestamp eg: 2022-02-16 13:34:00 (as integer)
     * 
     * @retrun string eg: 2022-02-16 00:00:00
     */    
    public static function getStartDayDateTime($timestamp){
        $startDayTimeDate = date("Y-m-d 00:00:00", $timestamp);
        return $startDayTimeDate;
    }

    
    /**
     * 
     */
    public static function getDatetimeByTimestamp($timestamp){
        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * 
     */
    public static function getDate_Ymd_ByTimestamp($timestamp){
        return date('Ymd', $timestamp);
    }

    /**
     * Repair result of database.
     * For example if a five minute period fetched from database, we consider that 
     * It must be have 5 item than each one of it related to a minute.
     * In case that a minute item missed in database.
     * This function add missed minute in correct possion of array and assign errorCount with NaN
     * That show a missed data.
     */
    public static function repair($result, $startTS, $endTS){
        for($time=$startTS, $i=0; $time<=$endTS; $time+=60, $i++){
            if(!isset($result[$i])){
                $result[$i]['dateTime'] = '';
            }
            if($result[$i]['dateTime'] != date('Y-m-d H:i', $time)){
                array_splice($result, $i, 0, [
                    ['dateTime' => date('Y-m-d H:i', $time), 'errorCount' => 'NaN']
                ]);
                $result = array_values($result);
            }
        }
        if($result[count($result)-1]['dateTime'] == ''){
            unset($result[count($result)-1]);
        }
        return $result;
    }
    
    public static function getPassedMinuteInDayFromDateTime($dateTime){
        $ts = strtotime($dateTime);
        $time = date('H:i:s', $ts);
        $timePart = explode(':', $time);
        $hour = (int) $timePart[0];
        $minute = (int) $timePart[1];
        return $hour*60+$minute;
    }

    public static function getPassedMinuteInDayFromTimestamp($ts){
        $time = date('H:i:s', $ts);
        $timePart = explode(':', $time);
        $hour = (int) $timePart[0];
        $minute = (int) $timePart[1];
        return $hour*60+$minute;
    }
    
    /**
    * Print a log line in script.
    * 
    * @param string $message to show.
    */
    public static function logPrinter($message){
        $appPidText = getmypid();  
        echo date('Y-m-d H:i:s,').$appPidText." > ".$message."\n";
   }

   /**
    * Get main period timestamp to build cache.
    * 
    * @param stdClass {endMainTS, startMainTS}
    */
   public static function getMainPeriodTimestamp($chartName, $refreshLastPointBasedMinute, $periodForFirstBuildCacheBasedMinute){
        $endMainTS = get_end_timestamp();
        $startMainTS  = 0;
        // Search for cache files.
        $cacheFiles = CacheChart::getCacheFileList($chartName);
        if(!empty($cacheFiles)){
            $startMainTS = CacheChart::findStartTimeToRebuildCache($cacheFiles, $refreshLastPointBasedMinute);
        }else{
            $startMainTS = $endMainTS - ($periodForFirstBuildCacheBasedMinute*60);
        }
        $result = new stdClass();
        $result->endMainTS = $endMainTS;
        $result->startMainTS = $startMainTS;
        return $result;
   }
}
