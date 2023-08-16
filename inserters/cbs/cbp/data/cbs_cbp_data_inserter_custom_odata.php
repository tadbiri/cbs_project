<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

// Cache talbles.

ini_set('memory_limit','8192M');

/**
 * cerrc_id
 * cerrc_code
 */
$vpn_user = query("SELECT * FROM vpn_user")->result;

/**
 * rg_id
 * rg_code
 */
$nonvpn_user = query("SELECT * FROM nonvpn_user")->result;



// end cache.

function newErrCode($cerrc_code){
    $cerrc_desc = "new-item";
    $query = "INSERT INTO cbs_cbp_data_err_code (cerrc_code, cerrc_desc) VALUES (?, ?)";
    return query($query, [$cerrc_code, $cerrc_desc]);
}

function newRGCode($RATING_GROUP){
    $rg_name = "new-item";
    $query = "INSERT INTO cbs_cbp_data_rg_code (rg_code, rg_name) VALUES (?, ?)";
    return query($query, [$RATING_GROUP, $rg_name]);
}

// start building public functions.

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
    $files = scandir($path);    

    foreach($files as $file){
        $fullPath = $path."/".$file;
        
        if(substr($file,0, 5) == "odata"){
           
            if(is_file($fullPath) && explode(".", $fullPath)[2] == "gz"){
                if($file == "." || $file == '..'){continue;}
                if (filesize($fullPath) == 0 ){continue;}
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

/**
 * get file path and get sql command for it.
 * 
 * @param string $file path of file to feed.
 * @return array an insertable array to database, [cbp_id, cerrc_id, count, cdr_date, cdr_time]
 */

function vpn_pyload($file){
    
     /**
     * All detected field to insert must have postfix like this. __FIELD__.
     */

    /**
     * In here data structure on this method and relation of it with database must be described.
     * Each row have this struct: 
     * [FIELD-NAME-ON-METHOD, DATA-TYPE, FIELD-NAME-ON-DB] 
     * 
     * All FIELD-NAME-ON-METHOD field must have postfix like this. __FIELD__.
     */
    $colsMap = [
        ['vpn', 'string', 'vpn'],
        ['rg', 'string', 'rg'],
        ['actual_usage', 'int', 'actual_usage'],
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
    $uniqKeyMap = [0,1];
    $countableFieldMap = [2];
    
    global $nonvpn_user,$vpn_user;
    
    $uniqCDRs = shell_exec("zcat $file | awk -F \"|\" '{ print $535,$40+$35,$42+$49,$26}'");
    $uniqCDRs = explode("\n", $uniqCDRs);
    
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 4){continue;}
        $rg__FIELD__ = $uniqCDR_Parts[0];
        $actual_usage__FIELD__ = $uniqCDR_Parts[1];
        $msisdn = $uniqCDR_Parts[2];

        $index = 0;
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($vpn_user, ['msisdn', $msisdn__FIELD__]);
                if($index == -1){
                    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($nonvpn_user, ['msisdn', $msisdn__FIELD__]);
                      if($index == -1){
                          echo "msisdn: $msisdn\n";
                          continue;
                      }
                      else{
                        $vpn___FIELD__ = '0';
                      }
                }else{
                    $vpn___FIELD__ = '1';
                }
        

        /**
         * Hold row and uniqKey for current item.
         */
        //$finish_milsecond = (microtime(true)-$start)*1000;
        //echo "buildFieldsTime: $finish_milsecond\n\n";

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


// End of requirements for inserter.

/**
 * Directory address that holds log files,
 * Database feeded from this directory to make data.
 */
//define('FEED_LOG_DIR', "/home/cbshome/failedcdr_analysis/data");
define('FEED_LOG_DIR', "/cbshome/cdr_analysis/data/cbs/data-test/mashhad");

//$city = getDirList(FEED_LOG_DIR);
while(true) {

                $fileList = getFilelist(FEED_LOG_DIR);

            

            /**
             * Iterate on files.
             */
            foreach($fileList as $file){
                $start1 = microtime(true);
               
                /**
                 * Ignore in case that file processing is not finished 
                 * Or file size not changed.
                 */
                if($file == null){
                    continue;
                }


                logPrinter("\n".dateLog()."Feed file: $fileNamePure \n");

                
            

            ///////////////////////////////// cbs_cbp_data_cell_inserter //////////////////////////////
                $start1 = microtime(true);
                
                $fileProcessedRows = vpn_pyload($file);

                

                logPrinter(dateLog()."Row conut in file: ".count($fileProcessedRows->result)."\n");

                $rows = $fileProcessedRows->result;

                usort($rows, function($a, $b){
                    return strcmp($a['__uniqKey__'], $b['__uniqKey__']);
                });

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
                        $statementsToUpdateOnConflict .= "$itemFieldIndex = vpn_pyload.$itemFieldIndex+excluded.$itemFieldIndex,";
                    }    
                }

                // Remove last comma char. 
                $columnNamesCSV = rtrim($columnNamesCSV, ",");
                $columnNamesUniqKeyCSV = rtrim($columnNamesUniqKeyCSV, ",");
                $statementsToUpdateOnConflict = rtrim($statementsToUpdateOnConflict, ",");

                // Make final sql command.
                $sql = "INSERT INTO vpn_pyload ($columnNamesCSV) VALUES $insertCSV 
                        ON CONFLICT ($columnNamesUniqKeyCSV) DO UPDATE
                        SET $statementsToUpdateOnConflict;";
               
                //print_r($sql);
                //echo "\n\n\n\n";    
                // Execute sql command and check status of it.
                $start = microtime(true);
                $queryStatus = query($sql)->status;
                if(!$queryStatus){
                    logPrinter(dateLog()."Error in query!\n");
                }

            $finish_second = (microtime(true)-$start)*1000;
            echo "queryinsert2: $finish_second\n";

            $finish_second1 = (microtime(true)-$start1)*1000;
            echo "TotalTime2--> $finish_second1\n\n";
            }
        
//    }
sleep(1);
}