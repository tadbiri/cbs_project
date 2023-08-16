<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */

require_once dirname(__DIR__, 1)."/config.php";

// Requirements for inserter

// Cache talbles.
/**
 * subkey_class_id
 * subkey_code_class_code
 */
$cbs_see_voice_subkey_class = query("SELECT subkey_class_id, CONCAT(subkey_code,',', class_code) AS subkey_code_class_code FROM cbs_see_voice_subkey_class")->result;

/**
 * see_id
 * see_code
 */
$cbs_see_name = query("SELECT * FROM cbs_see_name")->result;

/**
 * serrc_id
 * serrc_code
 */
$cbs_see_voice_err_code = query("SELECT serrc_id, serrc_code FROM cbs_see_voice_err_code")->result;
// end cache.


// Functions for insert not-found data.
function newSubkeyClass($subkey_code, $class_code){
    $subkey_class_desc = 'new-item';
    $query = "INSERT INTO cbs_see_voice_subkey_class (subkey_code, class_code, subkey_class_desc) VALUES (?, ?, ?)";
    return query($query, [$subkey_code, $class_code, $subkey_class_desc]);
}

function newErrCode($serrc_code){
    $serrc_desc = "new-item";
    $query = "INSERT INTO cbs_see_voice_err_code (serrc_code, serrc_desc) VALUES (?, ?)";
    return query($query, [$serrc_code, $serrc_desc]);
}

function dateLog(){
    return date('Y-m-d H:i:s')." => ";
}

function logPrinter($text){
    if(LOG_IN_INSERTER){
        echo $text;
    }
}


/**
 * Get file path and receive sql command for it.
 * 
 * @param string $file path of file to feed.
 * @return stdClass {
 *  result: array
 *  countableFieldMap: array
 *  colsMap: array
 *  uniqKeyMap: array 
 * }
 */
function insertFailedCDR($file){
    /**
     * In here data structure on this method and relation of it with database must be described.
     * Each row have this struct: 
     * [FIELD-NAME-ON-METHOD, DATA-TYPE, FIELD-NAME-ON-DB] 
     * 
     * All FIELD-NAME-ON-METHOD field must have postfix like this. __FIELD__.
     */
    $colsMap = [
        ['cdr_date_time', 'string', 'date_time'],
        ['ERROR_CODE', 'int', 'serrc_code'],
        ['regionId', 'int', 'region_id'],
        ['see_id', 'int', 'see_id'],
        ['subkey_class_id', 'int', 'subkey_class_id'],
        ['count', 'int', 'event_count']
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
    $countableFieldMap = [5];
    

    global $cbs_see_voice_subkey_class, $cbs_see_voice_err_code, $cbs_see_name;
    $seeCode = shell_exec("stat $file | head -n 1 | cut -d \".\" -f 3");
    $seeCode = trim($seeCode);
    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_name, ['see_code', $seeCode]);
    $see_id__FIELD__ = $cbs_see_name[$index]['see_id'];
    $regionId__FIELD__ = $cbs_see_name[$index]['region_id'];

    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $4,$8,$2,$3}' | sed 's/.//14' |  sed 's/.//13' | sort -k1 -k2 -k3 -k4 -n | uniq -f1 -f2 -f3 -c");
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 5){continue;}
        $count__FIELD__ = $uniqCDR_Parts[0];
        $dateTime = $uniqCDR_Parts[1];
        $ERROR_CODE__FIELD__ = $uniqCDR_Parts[2];
        $SUB_KEY = $uniqCDR_Parts[3];
        $SUB_KEY = trim($SUB_KEY);
        $CLASS_CODE = $uniqCDR_Parts[4];
        $CLASS_CODE = trim($CLASS_CODE);
        // 1,2 
        $subkey_class_id__FIELD__ = '0';
        $SUB_KEY_CODE_CLASS_CODE = $SUB_KEY.",".$CLASS_CODE;
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_voice_subkey_class, ['subkey_code_class_code', $SUB_KEY_CODE_CLASS_CODE]);
        // Check for find match.
        if($index == -1){
            // Not any match find, so make it and use it. 
            $subkey_class_id__FIELD__ = newSubkeyClass($SUB_KEY, $CLASS_CODE)->id;
            // Update cache for next time.
            $cbs_see_voice_subkey_class = query("SELECT subkey_class_id, CONCAT(subkey_code,',', class_code) AS subkey_code_class_code FROM cbs_see_voice_subkey_class");
        }else{
            $subkey_class_id__FIELD__ = $cbs_see_voice_subkey_class[$index]['subkey_class_id'];
        }

        $cdr_date = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7];
        $cdr_time = $dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11];
        $cdr_date_time__FIELD__ = $cdr_date." ".$cdr_time;
        

        /**
         * Hold row and uniqKey for current item.
         */
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
        
    }
    
    $final_result = new stdClass();
    $final_result->result = $results;
    $final_result->countableFieldMap = $countableFieldMap;
    $final_result->colsMap = $colsMap;
    $final_result->uniqKeyMap = $uniqKeyMap;
    
    return $final_result;
}


// End of requirements for inserter.

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
 * Get a path and return an array that hold files.
 * 
 * @param string $path eg: /var/www/html/
 * 
 * @return array [index.php, .htaccess]
 */
function getFilelist($path){
    $_res = [];
    $files = scandir($path);
    foreach($files as $file){
        if($file == "." || $file == '..'){continue;}
        $fullPath = $path."/".$file;
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
     */
    clearstatcache();
    $currentSetting = [
        date('Y-m-d H:i:s'),
        filesize($file),
        0,
        count($fileLineList)
    ];
        
    /**
     * Check that index file existed or not.
     */
    if(file_exists($directory.$fileNameWithoutExtension.".ndx")){ 
        $storedSetting = explode(",", file($directory.$fileNameWithoutExtension.".ndx")[0]);

        // Check that file changed.
        $storedLastLineIndex = (int) $storedSetting[3];
        if($storedLastLineIndex == $currentSetting[3]){
            logPrinter(dateLog()."Notic: The size of file '$fileNameWithoutExtension' not changed yet. \n");
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

//$cityList = getDirList(FEED_LOG_DIR);
//while(true) {
//    foreach($cityList as $city){
//        $dateDirList = getDirList($city);
//        foreach($dateDirList as $dateDir){
            $dateDir = FEED_LOG_DIR;
            $fileList = getFilelist($dateDir);

            /**
             * Control sub-directories that must be needed in each directory.
             */
            $tempFileDirectoryPath = $dateDir."temp-files/";
            $logFileDirectoryPath = $dateDir."logs/";

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


                /**
                 * Get pure file name to make helpers files.
                 * .log -> Hold eeverything about processing operation of this file. 
                 * .ndx -> Some tiny flag for store file size and readed period to detect file growing.  
                 * .tmp -> Hold small chunks from file to proccessing.
                 */
                $fileName = removePathFromFilePath($file);
                $fileNamePure = removeFileExtension($fileName);

                $logFilePath = $logFileDirectoryPath.$fileNamePure.".log";
                $indexFilePath = $tempFileDirectoryPath.$fileNamePure.".ndx";
                $fileTempPath = $tempFileDirectoryPath.$fileNamePure.".tmp";

                
                //logPrinter("\n".dateLog()."Feed file: $fileNamePure \n");

                $fileProcessedRows = insertFailedCDR($fileTempPath);
;
                //logPrinter(dateLog()."Row conut in file: ".count($fileProcessedRows->result)."\n");

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
                                $insertItem[$itemFieldIndex] += (int) $rows[$i][$itemFieldIndex];
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
                        $statementsToUpdateOnConflict .= "$itemFieldIndex = public.logs.$itemFieldIndex+excluded.$itemFieldIndex,";
                    }    
                }
                
                // Remove last comma char. 
                $columnNamesCSV = rtrim($columnNamesCSV, ",");
                $columnNamesUniqKeyCSV = rtrim($columnNamesUniqKeyCSV, ",");
                $statementsToUpdateOnConflict = rtrim($statementsToUpdateOnConflict, ",");
                
                // Make final sql command.
                $sql = "INSERT INTO public.logs ($columnNamesCSV) VALUES $insertCSV 
                        ON CONFLICT ($columnNamesUniqKeyCSV) DO UPDATE
                        SET $statementsToUpdateOnConflict;";
                
                // Execute sql command and check status of it.
                $queryStatus = query($sql)->status;
                if(!$queryStatus){
                    logPrinter(dateLog()."Error in query!\n");
                }

                //$start = microtime(true);
                //$finish_second = (microtime(true)-$start)*1000;
            }
//        }
//    }
//}