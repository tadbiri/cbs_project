<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */
require_once dirname(__DIR__, 1)."/config.php";

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
 *  uniqKeyMap: array
 *  otherFields: array
 *  columnToSum: string
 *  fullFieldMap: array
 * }
 */
function insertFailedCDR($file){
    /**
     * All detected field to insert must have postfix like this. __FIELD__.
     */

    /**
     * Set fields that must be considered as a key to 
     * Detect same items to sum them in latter. 
     */
    $uniqKeyMap = [
        ['cdr_date_time', 'string'],
        ['ERROR_CODE', 'int'],
        ['regionId', 'int'],
        ['see_id', 'int']
    ];

    /**
     * Other fields that not important in hash.
     * This feilds losted in final result, the first value for each chunk
     * Considered as final value.
     */
    $otherFields = [
        ['subkey_class_id', 'int'],
    ];


    // Colmn name that must be added based hash.
    $columnToSum = ['count', 'int'];
    // NOTE TO ME 
    // SET MULTI COL.

    $fullFieldMap = [];


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
        if(!$storedFinishProcessFlag){
            logPrinter(dateLog()."Notic: The insert process on file '$fileNameWithoutExtension' not finished yet. \n");
            return null;
        }
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


                $fileName = removePathFromFilePath($file);
                $fileNamePure = removeFileExtension($fileName);

                $logFilePath = $logFileDirectoryPath.$fileNamePure.".log";

                $indexFilePath = $tempFileDirectoryPath.$fileNamePure.".ndx";
                $file = $tempFileDirectoryPath.$fileNamePure.".tmp";

                
                logPrinter("\n".dateLog()."Feed file: $fileNamePure \n");

                $start = microtime(true);
                $fileProcessedRows = insertFailedCDR($file);
                logPrinter(dateLog()."Row conut in file: ".count($fileProcessedRows->result)."\n");

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
                        $insert_item[$fileProcessedRows->columnToSum[0]] += (int) $rows[$i][$fileProcessedRows->columnToSum[0]];
                    }
                    $insertTuple[] = $insert_item;
                }

                // Make query.
                $insertTupleCSV = "";
                
                $cols = [
                    ['date_time', 'cdr_date_time'], 
                    ['serrc_code', 'ERROR_CODE'], 
                    ['region_id', 'regionId'], 
                    ['see_id', 'see_id'],
                    ['subkey_class_id', 'subkey_class_id'],
                    ['event_count', 'count'],
                    
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
                $sql = "INSERT INTO public.logs VALUES $insertTupleCSV 
                        ON CONFLICT (date_time, serrc_code, region_id, see_id) DO UPDATE
                        SET event_count = public.logs.event_count+excluded.event_count;";

                
                $buildTime = (microtime(true)-$start)*1000;
                
                $start = microtime(true);
                $queryStatus = query($sql)->status;
                $finish_second = (microtime(true)-$start)*1000;
                logPrinter(dateLog()."buildTime: ".$buildTime.", QT:".$finish_second." \n");
                if(!$queryStatus){
                    logPrinter(dateLog()."Error in query \n");
                }
            }

            // Block B
//          
//        }
//    }
//}