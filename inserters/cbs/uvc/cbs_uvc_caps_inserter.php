<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

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
    $timestamp = strtotime(date('Y-m-d H:i:s'));
    $last_hour_timestamp = $timestamp-3600;
    $files = scandir($path);   

    foreach($files as $file){
        $fullPath = $path."/".$file;
        $fileDate= filectime($fullPath);
        if($file == "." || $file == '..'){continue;}
        // ($fileDate < $last_hour_timestamp){continue;}
        if(is_file($fullPath) && explode(".", $fullPath)[count(explode(".", $fullPath))-1] == "unl"){
            
                $_res[] = $fullPath;
                
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
 * Get an array that hold unl files, and manage index and other stuff about it.
 * 
 * @param string $file
 * @param string directory path of temp files.
 * 
 * @return string tempFile
 */
function updateIndex($file, $directory){

    /**
     * Get pure file name.
     * To make related temp and index files.
     * These files have a related name with main file but with another 
     * File extension, for index file use .ndx and temp file use .tmp.
     */
    $fileName = removePathFromFilePath($file);
    $fileNameWithoutExtension = removeFileExtension($fileName);
        
    // Get file lines.
    $fileLineList = file($file);

    /**
     * $Currentsetting variable hold detail of file that calculated in current run.
     * 
     * 0 Datetime of last read.
     * 1 Last file size per byte.
     * 2 Last Readed line number.
     * 3 Last line count in file.
     * 4 Finish process flag.
     */
    clearstatcache();
    $currentSetting = [
        date('Y-m-d H:i:s'),
        filesize($file),
        0,
        count($fileLineList),
        0
    ];
        
    /**
     * Check that index file existed or not.
     */
    if(file_exists($directory.$fileNameWithoutExtension.".ndx")){ 
        $storedSetting = explode(",", file($directory.$fileNameWithoutExtension.".ndx")[0]);

        // Check that process is finished.
        $storedFinishProcessFlag = (int) $storedSetting[4];
        /*if(!$storedFinishProcessFlag){
            logPrinter(dateLog()."Notic: The insert process on file '$fileNameWithoutExtension' not finished yet. \n");
            return null;
        }*/
        // Check that file changed.
        $storedLastLineIndex = (int) $storedSetting[3];
        if($storedLastLineIndex == $currentSetting[3]){
            //logPrinter(dateLog()."Notic: The size of file '$fileNameWithoutExtension' not changed yet. \n");
            return null;
        }
        /**
         * To update file setting.
         */
        clearstatcache();
        $currentSetting[0] = date('Y-m-d H:i:s');
        $currentSetting[1] = filesize($file);
        $currentSetting[2] = $storedLastLineIndex;
        $currentSetting[3] = count($fileLineList);
        $currentSetting[4] = 0;
    }

    // Write file setting in index file.
    $fp = fopen($directory.$fileNameWithoutExtension.".ndx", 'wr');
    fwrite($fp, TypeConvertorHelper::arrayToCSV($currentSetting));
    fclose($fp);
    /**
     * Make a new temp file.
     * In case that old file exist, delete old file.
     */
    $currentReadedLineIndex = (int) $currentSetting[2];
    $currentLastLineIndex = (int) $currentSetting[3];
    $tempFilePath = $directory.$fileNameWithoutExtension.".tmp";
    if(file_exists($tempFilePath)){
        unlink($tempFilePath);
    }
    
    $startLineNumber = $currentReadedLineIndex+1;
    $endLineNumber = $currentLastLineIndex; 
    exec("cat $file | sed -n '".$startLineNumber.",".$endLineNumber."p' >> $tempFilePath");
    return $tempFilePath;
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
 * @return array an insertable array to database, [see_id, subkey_class_id, serrc_id, count, cdr_date, cdr_time]
 */
function capsInserter($file){
    /**
     * All detected field to insert must have postfix like this. __FIELD__.
     */

    /**
     * Set fields that must be considered as a key to 
     * Detect same items to sum them in latter. 
     */
    $uniqKeyMap = [
        ['cdr_date_time', 'string'],
        ['uvcId', 'int'],
        ['event_type', 'int']
    ];

    /**
     * Other fields that not important in hash.
     * This feilds losted in final result, the first value for each chunk
     * Considered as final value.
     */
    $otherFields = [
        
    ];

    // Colmn name that must be added based hash.
    $columnToSum = ['CAPS', 'int'];
    // NOTE TO ME 
    // SET MULTI COL.

    $fullFieldMap = [];

    $event_type__FIELD__ = 1;

    $uniqCDRs = shell_exec("cat $file | awk -F \",\" '{print $2,$3,$4}'");
    $uniqCDRs = explode("\n", $uniqCDRs);

    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        //echo "Line : $uniqCDR \n";
        
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));

        $uniqCDR_Parts = explode(" ", $uniqCDR);
        //echo "uniqCDR_Parts: \n";
        //print_r($uniqCDR_Parts);

        if(count($uniqCDR_Parts) != 4){
            //echo "this line scaped for length of array error \n ";
            continue;
        }
        $cdr_date = $uniqCDR_Parts[1];
        $cdr_time = $uniqCDR_Parts[2];
        $cdr_date_time__FIELD__ = $cdr_date." ".$cdr_time;
        $CAPS__FIELD__ = $uniqCDR_Parts[3];
        $uvcId__FIELD__= $uniqCDR_Parts[0];
        

        // Calculate uniq key
        $uniqKey = "";
        foreach($uniqKeyMap as $ukm){
            $field_name = $ukm[0]."__FIELD__";
            if(isset($$field_name)){
                $uniqKey .= $$field_name;
            }else{
                logPrinter(dateLog()."ERROR: $field_name not Exist, check your define. \n");
                exit;
            }
        }
        $uniqKeyHash = md5($uniqKey);

        /**
         * Make row for final result.
         * Add calculated __uniqKey__ to row.
         * Add fields from uniqKeyMap. 
         */
        $row = []; 
        $row['__uniqKey__'] = $uniqKeyHash;
        foreach($uniqKeyMap as $ukm){
            $fullFieldMap[] = $ukm;
            $field_name = $ukm[0]."__FIELD__";
            if(!isset($$field_name)){
                logPrinter(dateLog()."ERROR: $field_name not Exist, check your define. \n");
                exit;
            }
            $row[$ukm[0]] = $$field_name;
        }

        // Add sum column.
        $field_name = $columnToSum[0]."__FIELD__";
        if(!isset($$field_name)){
            logPrinter(dateLog()."ERROR: $field_name not Exist for Sum, check your define. \n");
            exit;
        }
        $row[$columnToSum[0]] = $$field_name;
        $fullFieldMap[] = $columnToSum;

        foreach($otherFields as $field){
            $fullFieldMap[] = $field;
            $field_name = $field[0]."__FIELD__";
            if(!isset($$field_name)){
                logPrinter(dateLog()."ERROR: $field_name not Exist, check your define. \n");
                exit;
            }
            $row[$field[0]] = $$field_name;
        }
        $results[] = $row; 
    }
    $final_result = new stdClass();
    $final_result->result = $results;
    $final_result->uniqKeyMap = $uniqKeyMap;
    $final_result->otherFields = $otherFields;
    $final_result->columnToSum = $columnToSum;
    $final_result->fullFieldMap = $fullFieldMap;

    return $final_result;
}
// End of requirements for inserter.



/**
 * Directory address that holds log files,
 * Database feeded from this directory to make data.
 */
//define('FEED_LOG_DIR', "/home/cbshome/failedcdr_analysis/data");
define('FEED_LOG_DIR', "/cbshome/cdr_analysis/data/cbs/uvc");

//$city = getDirList(FEED_LOG_DIR);
while(true) {
    //foreach($cityList as $city){
        //print_r($city);
        //echo "\n";
        //$dateDirList = getDirList(FEED_LOG_DIR);
        //foreach($dateDirList as $dateDir){
            //print_r($dateDir);
            //echo "\n";
            //$dateDir = FEED_LOG_DIR;
            $fileList = getFilelist(FEED_LOG_DIR);
            //print_r($fileList);
            //echo "\n"; 
          
            /**
             * Control sub-directories that must be needed in each directory.
             */
            $tempFileDirectoryPath = FEED_LOG_DIR."/"."temp-files/";
            $logFileDirectoryPath = FEED_LOG_DIR."/"."logs/";

            clearstatcache();
            if(!is_dir($tempFileDirectoryPath)){
                mkdir($tempFileDirectoryPath);
            }
            if(!is_dir($logFileDirectoryPath)){
                mkdir($logFileDirectoryPath);
            }


            /**
             * Iterate on files.
             */
            foreach($fileList as $file){
                // Make indexes and more detail inside the function.
                $file = updateIndex($file, $tempFileDirectoryPath);

                // NOTE TO ME
                // REMOVE CONTROL OF PROCEESSING.
                
                /**
                 * Ignore in case that file processing is not finished 
                 * Or file size not changed.
                 */
                if($file == null){
                    continue;
                }
               
                $fileName = removePathFromFilePath($file);
                $fileNamePure = removeFileExtension($fileName);
               
                $logFilePath = $logFileDirectoryPath.$fileNamePure.".log";

                $indexFilePath = $tempFileDirectoryPath.$fileNamePure.".ndx";
                $file = $tempFileDirectoryPath.$fileNamePure.".tmp";

                logPrinter("\n".dateLog()."Feed file: $fileNamePure \n");
                
                /////////////////////////////////////////////////////////////CAPS
                $start = microtime(true);
                $fileProcessedRows = capsInserter($file);
                logPrinter(dateLog()."CAPS Row conut in file: ".count($fileProcessedRows->result)."\n");

                $rows = $fileProcessedRows->result;

                usort($rows, function($a, $b){
                    return strcmp($a['__uniqKey__'], $b['__uniqKey__']);
                });

                $uniqKeyList = array_column($rows, '__uniqKey__');
                $uniqKeyListUniqed = array_unique($uniqKeyList);

                /**
                 * Make map.
                 * List [a, b, c]
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

                $insertTuple = [];
                foreach($map as $e){
                    $insert_item = [];
                    $insert_item[$fileProcessedRows->columnToSum[0]] = 0;

                    foreach($fileProcessedRows->uniqKeyMap as $field){
                        $insert_item[$field[0]] = $rows[$e[0]][$field[0]];
                    }
                    foreach($fileProcessedRows->otherFields as $field){
                        $insert_item[$field[0]] = $rows[$e[0]][$field[0]];
                    }
                    
                    for($i=$e[0]; $i<=$e[1]; $i++){
                        $insert_item[$fileProcessedRows->columnToSum[0]] += $rows[$i][$fileProcessedRows->columnToSum[0]];
                    }
                    $insertTuple[] = $insert_item;
                }

                // Make query.
                $insertTupleCSV = "";
                
                $cols = [
                    ['cdr_date_time', 'cdr_date_time'], 
                    ['uvc_id', 'uvcId'], 
                    ['event_type_id', 'event_type'],
                    ['e_count', 'CAPS'],
                ];
                
                foreach($insertTuple as $insertItem){
                    $insertTupleCSV .= "(";
                    foreach($cols as $col){

                        $fieldName = $col[1];
                        $fieldValue = $insertItem[$col[1]];

                        $fieldType = $fileProcessedRows->fullFieldMap[array_search($fieldName, array_map(function($i){
                            return $i[0];
                        }, $fileProcessedRows->fullFieldMap))][1]."\n";
                        
                        if(trim($fieldType) == 'string'){
                            $insertTupleCSV .= "'".$fieldValue."'".",";
                        }else{
                            $insertTupleCSV .= $fieldValue.",";
                        }
                    }

                    
                    $insertTupleCSV = rtrim($insertTupleCSV, ",");
                    $insertTupleCSV .= "),";    
                }
                $insertTupleCSV = rtrim($insertTupleCSV, ",");
                $sql = "INSERT INTO cbs_uvc_log VALUES $insertTupleCSV 
                ON CONFLICT ON CONSTRAINT cbs_uvc_log_uniq_cons DO UPDATE
                SET e_count = excluded.e_count;
                COMMIT;";

                $buildTime = (microtime(true)-$start)*1000;
                
                $start = microtime(true);
                $queryStatus = query($sql)->status;
                $finish_second = (microtime(true)-$start)*1000;
                logPrinter(dateLog()."buildTime(ms): ".$buildTime.", InsertTime(ms):".$finish_second." \n");
                if(!$queryStatus){
                    logPrinter(dateLog()."Error in query \n");
                }

            }          
        //}
//    }
sleep(3);
}
