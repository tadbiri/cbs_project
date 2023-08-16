<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

/**
 * Set timezone for app in tehran.
 */
date_default_timezone_set("Asia/Tehran");

function dateLog(){
    return date('Y-m-d H:i:s')." => ";
}

function logPrinter($text){
    if(LOG_IN_INSERTER){
        echo $text;
    }
}
$cbs_cell_map = query("SELECT city_code,province_code FROM cbs_cell_map")->result;

/**
 * Get a path and return an array that hold directories.
 * 
 * @param string $path eg: /var/www/html/
 * 
 * @return array [www, cgi-bin]
 */
function getDirList($path){
    $_res = [];
    $files = scandir($path);
    foreach($files as $file){
        if($file == "." || $file == '..'){continue;}
        $fullPath = $path."/".$file;
        if(is_dir($fullPath)){
            $_res[] = $fullPath;
        }
    }
    return $_res;
}

/**
 * Get a path and return an array that hold files for last hour.
 * 
 * @param string $path eg: /var/www/html/
 * 
 * @return array [index.php, .htaccess]
 */
function getFilelist($path){
    $_res = [];
    $timestamp = strtotime(date('Y-m-d H:i:s'));
    $last_hour_timestamp = $timestamp-172800;
    $files = scandir($path);   

    foreach($files as $file){
        $fullPath = $path."/".$file;
        if(substr($file,0, 4) === "orec"){
            if(is_file($fullPath) && explode(".", $fullPath)[count(explode(".", $fullPath))-1] == "sec"){
                $fileDate= filectime($fullPath);
                if($file == "." || $file == '..'){continue;}
                if (filesize($fullPath) == 0 ){continue;}
                if ($fileDate < $last_hour_timestamp){continue;}
                    $_res[] = $fullPath;
            }      
        } 
    }
    return $_res;
}




/**
 * Get file name and remove file extension.
 * 
 * @param string $fileName eg: man-az-khodame-hayede.mp3
 * 
 * @return string eg: man-az-khodame-hayede
 */
function removeFileExtension($fileName){
    $fileNameParts = explode(".", $fileName);
    unset($fileNameParts[count($fileNameParts)-1]);
    $_fileName = "";
    foreach($fileNameParts as $fileNamePart){
        $_fileName .= $fileNamePart.".";
    }
    $_fileName = substr($_fileName, 0, -1);
    return $_fileName;
}

/**
 * Get a file full path and remove path.
 * 
 * @param string $file eg: /var/www/html/index.php
 * 
 * @return string eg: index.php
 */
function removePathFromFilePath($file){
    $fileParts = explode("/", $file);
    $fileName = $fileParts[count($fileParts)-1];
    return $fileName;
}



/**
 * Just make a md5 hash 
 * It's stronger than normal md5 function.
 */
function makeMd5($salt){
    return md5(str_replace(".", "-", uniqid('', true)).$salt.microtime().rand());
}
//////////////////////////// end of public functions

function recSecLog($file){
    global $cbs_cell_map;
    /**
     * In here data structure on this method and relation of it with database must be described.
     * Each row have this struct: 
     * [FIELD-NAME-ON-METHOD, DATA-TYPE, FIELD-NAME-ON-DB] 
     * 
     * All FIELD-NAME-ON-METHOD field must have postfix like this. __FIELD__.
     */
    $colsMap = [
        ['cdr_date_time', 'string', 'cdr_date_time'],
        ['call_min', 'string', 'call_min'],
        ['area_code', 'string', 'area_code'],
        ['cell_code', 'string', 'cell_code'],
        ['e_count', 'int', 'e_count'],
        ];

    /**
     * Config uniqKey, other-fields and countable-fields in here.
     * 
     * For example with numbers 0,1 fields cdr_date_time,ERROR_CODE considered 
     * As key with together to make a hash for detect same rows.
     * 
     * Colmns that must be summed based hash.
     * 
     * Other fields that not important in hash.
     * This feilds losted in final result, the first value for each chunk
     * Considered as final value. 
     * 
     */
    $uniqKeyMap = [0,1,2,3];
    $countableFieldMap = [4];
 
    $results = [];
        
            $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{ if(($514 != 9891100722 || $514 != 9891100724)) print $13,$35,$525,$501,$524}' | sort -k1 -k2 -k3 -k4 -k5 -n | uniq -c");
            $uniqCDRs = explode("\n", $uniqCDRs);
            // an array to hold results.


            //$finish_milsecond = (microtime(true)-$start)*1000;
            //echo "cellInserterFuncStartawk: $finish_milsecond\n\n";

            foreach($uniqCDRs as $uniqCDR){

                if(empty($uniqCDR)){continue;}  
            
                //$start = microtime(true);
                $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
                $uniqCDR_Parts = explode(" ", $uniqCDR);

                if(count($uniqCDR_Parts) != 6){continue;}

                $e_count__FIELD__ = $uniqCDR_Parts[0];
                $dateTime = $uniqCDR_Parts[1];
                $call_min = $uniqCDR_Parts[2];
                $cell_code = $uniqCDR_Parts[4];
                if($call_min <= '5'){
                    $call_min__FIELD__ = '5';
                }elseif($call_min <= '15' && $call_min > '5'){
                    $call_min__FIELD__ = '15';
                }elseif($call_min <= '30' && $call_min > '15'){
                    $call_min__FIELD__ = '30';
                }elseif($call_min <= '60' && $call_min > '30'){
                    $call_min__FIELD__ = '60';
                }else{
                    $call_min__FIELD__ = '61';
                }

                if($uniqCDR_Parts[5] == '98'){
                    $city_code = substr($cell_code,7,2);
                        $index1 = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cell_map, ['city_code', $city_code]);
                        if($index1 != -1){
                            $area_code__FIELD__ = $cbs_cell_map[$index1]['province_code'];
                        }else{
                            $area_code__FIELD__ = "0000";
                            if(empty($cell_code__FIELD__)){
                                $cell_code__FIELD__ = '0000';
                        }
                            
                        }
                            
                }else{
                    $area_code__FIELD__ = 'r'.$uniqCDR_Parts[5];
                    $cell_code__FIELD__ = $uniqCDR_Parts[3];
                }

                $cell_code__FIELD__ = $uniqCDR_Parts[4];
                if(empty($cell_code__FIELD__)){
                    $cell_code__FIELD__ ='0';
                }
            
                $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
                $cdr_date_time = strtotime("$cdr_date_time");
                $cdr_date_time = $cdr_date_time+(60*60*3.5);
                $dateTime =  date('Y-m-d H:i', $cdr_date_time);
                $cdr_date_time__FIELD__ =  $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[5].$dateTime[6]."-".$dateTime[8].$dateTime[9]." ".$dateTime[11].$dateTime[12].":"."0"."0".":"."0"."0";

                //$start = microtime(true);
                $row = []; 
                $uniqKey = "";
                foreach($colsMap as $index => $colMap){
                    $fieldNameOnMethod = $colMap[0];
                    $dataType = $colMap[1];
                    $fieldNameOnDb = $colMap[2];

                    $field_name = $fieldNameOnMethod."__FIELD__";
                    if(!isset($$field_name)){
                        logPrinter(dateLog()."ERROR: $field_name not Exist, check your define. \n");
                        exit;
                    }

                    // Detect uniqKeyMap.
                    if(in_array($index, $uniqKeyMap)){
                        $uniqKey .= $$field_name;
                    }

                    // Detect type and change value based on it.
                    if($dataType == 'string'){
                        $$field_name = "'".$$field_name."'";
                    }

                    // Add row.
                    $row[$fieldNameOnDb] = $$field_name;
                }

                // Calculate hash and append it.
                $uniqKeyHash = md5($uniqKey);
                $row['__uniqKey__'] = $uniqKeyHash;

                $results[] = $row;
                //$finish_milsecond = (microtime(true)-$start)*1000;
                //echo "mapTime: $finish_milsecond\n\n";
            }
    //$start = microtime(true);
    $final_result = new stdClass();
    $final_result->result = $results;
    $final_result->countableFieldMap = $countableFieldMap;
    $final_result->colsMap = $colsMap;
    $final_result->uniqKeyMap = $uniqKeyMap;
    //$finish_milsecond = (microtime(true)-$start)*1000;
    //echo "cellInserterFuncStartPart2: $finish_milsecond\n\n";

    return $final_result;
}

#############################################################################

function imsSecLog($file){
    global $cbs_cell_map;
    
    /**
     * In here data structure on this method and relation of it with database must be described.
     * Each row have this struct: 
     * [FIELD-NAME-ON-METHOD, DATA-TYPE, FIELD-NAME-ON-DB] 
     * 
     * All FIELD-NAME-ON-METHOD field must have postfix like this. __FIELD__.
     */
    $colsMap = [
        ['cdr_date_time', 'string', 'cdr_date_time'],
        ['call_min', 'string', 'call_min'],
        ['area_code', 'string', 'area_code'],
        ['cell_code', 'string', 'cell_code'],
        ['e_count', 'int', 'e_count'],
        ];

    /**
     * Config uniqKey, other-fields and countable-fields in here.
     * 
     * For example with numbers 0,1 fields cdr_date_time,ERROR_CODE considered 
     * As key with together to make a hash for detect same rows.
     * 
     * Colmns that must be summed based hash.
     * 
     * Other fields that not important in hash.
     * This feilds losted in final result, the first value for each chunk
     * Considered as final value. 
     * 
     */
    $uniqKeyMap = [0,1,2,3];
    $countableFieldMap = [4];
 
    $results = [];
        
            $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{ if(($514 == 9891100722 || $514 == 9891100724)) print $13,$35,$525,$501,$524}' | sort -k1 -k2 -k3 -k4 -k5 -n | uniq -c");
            $uniqCDRs = explode("\n", $uniqCDRs);
            // an array to hold results.


            //$finish_milsecond = (microtime(true)-$start)*1000;
            //echo "cellInserterFuncStartawk: $finish_milsecond\n\n";

            foreach($uniqCDRs as $uniqCDR){

                if(empty($uniqCDR)){continue;}  
            
                //$start = microtime(true);
                $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
                $uniqCDR_Parts = explode(" ", $uniqCDR);

                if(count($uniqCDR_Parts) != 6){continue;}

                $e_count__FIELD__ = $uniqCDR_Parts[0];
                $dateTime = $uniqCDR_Parts[1];
                $call_min = $uniqCDR_Parts[2];
                $cell_code = $uniqCDR_Parts[4];
                if($call_min <= '5'){
                    $call_min__FIELD__ = '5';
                }elseif($call_min <= '15' && $call_min > '5'){
                    $call_min__FIELD__ = '15';
                }elseif($call_min <= '30' && $call_min > '15'){
                    $call_min__FIELD__ = '30';
                }elseif($call_min <= '60' && $call_min > '30'){
                    $call_min__FIELD__ = '60';
                }else{
                    $call_min__FIELD__ = '61';
                }

                if($uniqCDR_Parts[5] == '98'){
                    $city_code = substr($cell_code,7,2);
                        $index1 = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cell_map, ['city_code', $city_code]);
                        if($index1 != -1){
                            $area_code__FIELD__ = $cbs_cell_map[$index1]['province_code'];
                        }else{
                            $area_code__FIELD__ = "0000";
                            if(empty($cell_code__FIELD__)){
                                $cell_code__FIELD__ = '0000';
                        }
                            
                        }
                            
                }else{
                    $area_code__FIELD__ = 'r'.$uniqCDR_Parts[5];
                    $cell_code__FIELD__ = $uniqCDR_Parts[3];
                }

                $cell_code__FIELD__ = $uniqCDR_Parts[4];
                if(empty($cell_code__FIELD__)){
                    $cell_code__FIELD__ ='0';
                }
            
                $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
                $cdr_date_time = strtotime("$cdr_date_time");
                $cdr_date_time = $cdr_date_time+(60*60*3.5);
                $dateTime =  date('Y-m-d H:i', $cdr_date_time);
                $cdr_date_time__FIELD__ =  $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[5].$dateTime[6]."-".$dateTime[8].$dateTime[9]." ".$dateTime[11].$dateTime[12].":"."0"."0".":"."0"."0";

                //$start = microtime(true);
                $row = []; 
                $uniqKey = "";
                foreach($colsMap as $index => $colMap){
                    $fieldNameOnMethod = $colMap[0];
                    $dataType = $colMap[1];
                    $fieldNameOnDb = $colMap[2];

                    $field_name = $fieldNameOnMethod."__FIELD__";
                    if(!isset($$field_name)){
                        logPrinter(dateLog()."ERROR: $field_name not Exist, check your define. \n");
                        exit;
                    }

                    // Detect uniqKeyMap.
                    if(in_array($index, $uniqKeyMap)){
                        $uniqKey .= $$field_name;
                    }

                    // Detect type and change value based on it.
                    if($dataType == 'string'){
                        $$field_name = "'".$$field_name."'";
                    }

                    // Add row.
                    $row[$fieldNameOnDb] = $$field_name;
                }

                // Calculate hash and append it.
                $uniqKeyHash = md5($uniqKey);
                $row['__uniqKey__'] = $uniqKeyHash;

                $results[] = $row;
                //$finish_milsecond = (microtime(true)-$start)*1000;
                //echo "mapTime: $finish_milsecond\n\n";
            }
    //$start = microtime(true);
    $final_result = new stdClass();
    $final_result->result = $results;
    $final_result->countableFieldMap = $countableFieldMap;
    $final_result->colsMap = $colsMap;
    $final_result->uniqKeyMap = $uniqKeyMap;
    //$finish_milsecond = (microtime(true)-$start)*1000;
    //echo "cellInserterFuncStartPart2: $finish_milsecond\n\n";

    return $final_result;
}



/**
 * Directory address that holds log files,
 * Database feeded from this directory to make data.
 */
define('FEED_LOG_DIR', "/cbshome/cdr_analysis/data/cbs/cbp/rec/mashhad");
//$cityList = getDirList(FEED_LOG_DIR);
while(true) {
//foreach($cityList as $city){
        //print_r($city);
        //echo "\n";
        
        $dateDirList = getDirList(FEED_LOG_DIR);
        foreach($dateDirList as $dateDir){
            //print_r($dateDir);
            //echo "\n";
            //$dateDir = FEED_LOG_DIR;
            $fileList = getFilelist($dateDir);
            //print_r($fileList);
            //echo "\n"; 

            /**
             * Iterate on files.
             */
            foreach($fileList as $file){
                if($file == null){
                    continue;
                }

            ############################################## recSecLog ######################################## 

                //logPrinter("\n".dateLog()."Feed file: $fileNamePure \n");
                $start1 = microtime(true);
                $start = microtime(true);
                $fileProcessedRows = recSecLog($file);
                $finish_second = (microtime(true)-$start)*1000;
                echo "inserterCelltime: $finish_second\n";

                //logPrinter(dateLog()."Row conut in file: ".count($fileProcessedRows->result)."\n");
                $start = microtime(true);
                //remove temp file
                //unlink($file);

               // $f = @fopen("$file", "r+");
               // if ($f !== false) {
               //     ftruncate($f, 0);
               //     fclose($f);
               // }
                if(!empty($fileProcessedRows->result)){
                    $rows = $fileProcessedRows->result;

                    /**
                     * Sort result based __uniqKey__.
                     * It's used for detect periods between chunks.
                     */
                    usort($rows, function($a, $b){
                        return strcmp($a['__uniqKey__'], $b['__uniqKey__']);
                    });
                    /**
                     * Get an array from all __uniqKey__.
                     * Uniq them to detect periods between chunks.
                     */
                    $uniqKeyList = array_column($rows, '__uniqKey__');
                    $uniqKeyListUniqed = array_unique($uniqKeyList);
                    /**
                     * Make map of period.
                     * List [a, b]
                     * a -> start index in pureArrayList.
                     * b -> end index in pureArrayList.
                     */
                    $map = [];
                    TypeConvertorHelper::repairArrayIndex($uniqKeyListUniqed);
                    for($i=0; $i<count($uniqKeyListUniqed); $i++){    
                        $StartIndex = array_search($uniqKeyListUniqed[$i], $uniqKeyList);
                        $map[] = [$StartIndex, 0];
                        if($i != 0){
                            $map[$i-1][1] = $StartIndex-1;
                        }
                    }
                    $map[count($map)-1][1] = count($rows)-1;

                    /**
                     * Make insertable sql command by map and rows.
                     */
                    $insertCSV = "";
                    foreach($map as $e){
                        // Get start and end period indexes.
                        $startPeriodIndex = $e[0];
                        $endPeriodIndex = $e[1];

                        // Get first row for each period.
                        $insertItem = $rows[$startPeriodIndex];

                        // Remove hash field from it.
                        unset($insertItem['__uniqKey__']);

                        // Iterate on remaining current period to calculate sums.
                        for($i=$startPeriodIndex+1; $i<=$endPeriodIndex; $i++){
                            // Iterate on fields to detect countable fields.
                            for($j=0; $j<count($fileProcessedRows->colsMap); $j++){
                                if(in_array($j, $fileProcessedRows->countableFieldMap)){
                                    $itemFieldIndex = $fileProcessedRows->colsMap[$j][2];
                                    $insertItem[$itemFieldIndex] += $rows[$i][$itemFieldIndex];
                                }    
                            }
                        }
                        /**
                         * Make a string like this :)
                         * (a1, a2, a3), (b1, b2, b3), (c1, c2, c3), ...
                         */
                        $insertCSV .= "(".TypeConvertorHelper::arrayToCSV($insertItem)."),";
                    }
                    // Remove last comma char.
                    $insertCSV = rtrim($insertCSV, ",");


                    /**
                     * Detect column-names and key and other stuff
                     * For make query.
                     */
                    $columnNamesCSV = "";
                    $columnNamesUniqKeyCSV = "";
                    $statementsToUpdateOnConflict = "";
                    // Iterate on defined fields.
                    for($i=0; $i<count($fileProcessedRows->colsMap); $i++){
                        // Get field-name that recognized to insert DB.
                        $itemFieldIndex = $fileProcessedRows->colsMap[$i][2];

                        // Make column-names.
                        $columnNamesCSV .= $itemFieldIndex.",";

                        // Make column-names for uniq key.
                        if(in_array($i, $fileProcessedRows->uniqKeyMap)){
                            $columnNamesUniqKeyCSV .= $itemFieldIndex.",";
                        } 

                        // 
                        if(in_array($i, $fileProcessedRows->countableFieldMap)){
                            $statementsToUpdateOnConflict .= "$itemFieldIndex = cbs_cbp_rec_sec_log.$itemFieldIndex+excluded.$itemFieldIndex,";
                        }    
                    }

                    // Remove last comma char. 
                    $columnNamesCSV = rtrim($columnNamesCSV, ",");
                    $columnNamesUniqKeyCSV = rtrim($columnNamesUniqKeyCSV, ",");
                    $statementsToUpdateOnConflict = rtrim($statementsToUpdateOnConflict, ",");

                    // Make final sql command.
                    $sql = "INSERT INTO cbs_cbp_rec_sec_log ($columnNamesCSV) VALUES $insertCSV 
                            ON CONFLICT ($columnNamesUniqKeyCSV) DO UPDATE
                            SET $statementsToUpdateOnConflict;";
                    
                    $finish_second = (microtime(true)-$start)*1000;
                    echo "mapBuildQueryIms: $finish_second\n";
                    //print_r($sql);
                    //echo "\n\n\n\n";    
                    // Execute sql command and check status of it.
                    $start = microtime(true);
                    $queryStatus = query($sql)->status;
                    if(!$queryStatus){
                        logPrinter(dateLog()."Error in query!\n");
                    }

                    $finish_second = (microtime(true)-$start)*1000;
                    echo "queryinserttimeIms: $finish_second\n";

                    $finish_second1 = (microtime(true)-$start1)*1000;
                    echo "TotalTimeIms--> $finish_second1\n\n";

                }
 
            ############################################## imsSecLog ######################################## 

                //logPrinter("\n".dateLog()."Feed file: $fileNamePure \n");
                $start1 = microtime(true);
                $start = microtime(true);
                $fileProcessedRows = imsSecLog($file);
                $finish_second = (microtime(true)-$start)*1000;
                echo "inserterCelltime: $finish_second\n";

                //logPrinter(dateLog()."Row conut in file: ".count($fileProcessedRows->result)."\n");
                $start = microtime(true);
                //remove temp file
                unlink($file);

               // $f = @fopen("$file", "r+");
               // if ($f !== false) {
               //     ftruncate($f, 0);
               //     fclose($f);
               // }

               if(!empty($fileProcessedRows->result)){
                    $rows = $fileProcessedRows->result;

                    /**
                     * Sort result based __uniqKey__.
                     * It's used for detect periods between chunks.
                     */
                    usort($rows, function($a, $b){
                        return strcmp($a['__uniqKey__'], $b['__uniqKey__']);
                    });
                    /**
                     * Get an array from all __uniqKey__.
                     * Uniq them to detect periods between chunks.
                     */
                    $uniqKeyList = array_column($rows, '__uniqKey__');
                    $uniqKeyListUniqed = array_unique($uniqKeyList);
                    /**
                     * Make map of period.
                     * List [a, b]
                     * a -> start index in pureArrayList.
                     * b -> end index in pureArrayList.
                     */
                    $map = [];
                    TypeConvertorHelper::repairArrayIndex($uniqKeyListUniqed);
                    for($i=0; $i<count($uniqKeyListUniqed); $i++){    
                        $StartIndex = array_search($uniqKeyListUniqed[$i], $uniqKeyList);
                        $map[] = [$StartIndex, 0];
                        if($i != 0){
                            $map[$i-1][1] = $StartIndex-1;
                        }
                    }
                    $map[count($map)-1][1] = count($rows)-1;

                    /**
                     * Make insertable sql command by map and rows.
                     */
                    $insertCSV = "";
                    foreach($map as $e){
                        // Get start and end period indexes.
                        $startPeriodIndex = $e[0];
                        $endPeriodIndex = $e[1];

                        // Get first row for each period.
                        $insertItem = $rows[$startPeriodIndex];

                        // Remove hash field from it.
                        unset($insertItem['__uniqKey__']);

                        // Iterate on remaining current period to calculate sums.
                        for($i=$startPeriodIndex+1; $i<=$endPeriodIndex; $i++){
                            // Iterate on fields to detect countable fields.
                            for($j=0; $j<count($fileProcessedRows->colsMap); $j++){
                                if(in_array($j, $fileProcessedRows->countableFieldMap)){
                                    $itemFieldIndex = $fileProcessedRows->colsMap[$j][2];
                                    $insertItem[$itemFieldIndex] += $rows[$i][$itemFieldIndex];
                                }    
                            }
                        }
                        /**
                         * Make a string like this :)
                         * (a1, a2, a3), (b1, b2, b3), (c1, c2, c3), ...
                         */
                        $insertCSV .= "(".TypeConvertorHelper::arrayToCSV($insertItem)."),";
                    }
                    // Remove last comma char.
                    $insertCSV = rtrim($insertCSV, ",");


                    /**
                     * Detect column-names and key and other stuff
                     * For make query.
                     */
                    $columnNamesCSV = "";
                    $columnNamesUniqKeyCSV = "";
                    $statementsToUpdateOnConflict = "";
                    // Iterate on defined fields.
                    for($i=0; $i<count($fileProcessedRows->colsMap); $i++){
                        // Get field-name that recognized to insert DB.
                        $itemFieldIndex = $fileProcessedRows->colsMap[$i][2];

                        // Make column-names.
                        $columnNamesCSV .= $itemFieldIndex.",";

                        // Make column-names for uniq key.
                        if(in_array($i, $fileProcessedRows->uniqKeyMap)){
                            $columnNamesUniqKeyCSV .= $itemFieldIndex.",";
                        } 

                        // 
                        if(in_array($i, $fileProcessedRows->countableFieldMap)){
                            $statementsToUpdateOnConflict .= "$itemFieldIndex = cbs_cbp_ims_sec_log.$itemFieldIndex+excluded.$itemFieldIndex,";
                        }    
                    }

                    // Remove last comma char. 
                    $columnNamesCSV = rtrim($columnNamesCSV, ",");
                    $columnNamesUniqKeyCSV = rtrim($columnNamesUniqKeyCSV, ",");
                    $statementsToUpdateOnConflict = rtrim($statementsToUpdateOnConflict, ",");

                    // Make final sql command.
                    $sql = "INSERT INTO cbs_cbp_ims_sec_log ($columnNamesCSV) VALUES $insertCSV 
                            ON CONFLICT ($columnNamesUniqKeyCSV) DO UPDATE
                            SET $statementsToUpdateOnConflict;";
                    
                    $finish_second = (microtime(true)-$start)*1000;
                    echo "mapBuildQueryIms: $finish_second\n";
                    //print_r($sql);
                    //echo "\n\n\n\n";    
                    // Execute sql command and check status of it.
                    $start = microtime(true);
                    $queryStatus = query($sql)->status;
                    if(!$queryStatus){
                        logPrinter(dateLog()."Error in query!\n");
                    }

                    $finish_second = (microtime(true)-$start)*1000;
                    echo "queryinserttimeIms: $finish_second\n";

                    $finish_second1 = (microtime(true)-$start1)*1000;
                    echo "TotalTimeIms--> $finish_second1\n\n";
                }            
            }
        }
//    }
sleep(1);
}

