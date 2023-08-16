<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

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
///////////////////////////////////////////////////// end cache.

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
    $last_hour_timestamp = $timestamp-172800;
    $files = scandir($path);   

    foreach($files as $file){
        $fullPath = $path."/".$file;
        if(is_file($fullPath) && explode(".", $fullPath)[count(explode(".", $fullPath))-1] == "unl"){
            $fileDate= filectime($fullPath);
            if($file == "." || $file == '..'){continue;}
            if (filesize($fullPath) == 0 ){continue;}
            if ($fileDate < $last_hour_timestamp){continue;}
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

function searchIndexFile($ndxfile, $searchName){
    /**
     * Get related file line in ndx db base on file name
     */
    $line_number = false;
    if ($handle = fopen($ndxfile, "r")) {
        $count = 0;
        while (($line = fgets($handle, 4096)) !== FALSE and !$line_number) {
            $count++;
            if (strpos($line, $searchName) !== FALSE){
                $line_number = $count;
                $index_line_content = $line;
                break;
            }else{
                $line_number = 0;
                $index_line_content = 0;
            }
        }
        fclose($handle);
        return array($line_number, $index_line_content);
    }
}

function updateNdxFile($ndxfile, $line_number, $index_line_content, $index_line_content_new, $action, $ndxtmpfile){
    if ($action == "append") {
        echo "start appending file\n\n";
        file_put_contents($ndxfile, "$index_line_content\n", FILE_APPEND | LOCK_EX);
    }
    if ($action == "replace") {
        echo "start replacing file\n\n";
        $reading = fopen($ndxfile, 'r');
        $writing = fopen($ndxtmpfile, 'w');
        $replaced = false;

        while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line, $index_line_content)) {
                $line = "$index_line_content_new\n";
                $replaced = true;
                echo "replaced \n";
            }
            fputs($writing, $line);
        }
        fclose($reading); fclose($writing);
        // might as well not overwrite the file if we didn't replace anything
        if ($replaced) {
            copy($ndxtmpfile, $ndxfile);
        } 
    }

}

/**
 * Get an array that hold unl files, and manage index and other stuff about it.
 * 
 * @param string $file
 * @param string directory path of temp files.
 * 
 * @return string tempFile
 */
function updateIndex($file, $directory , $ndxfile, $ndxtmpfile){

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
    $ndxFileArray = file($ndxfile);

    /**
     * $Currentsetting variable hold detail of file that calculated in current run.
     * 
     * 0 file name
     * 1 Datetime of last read.
     * 2 Last file size per byte.
     * 3 Last Readed line number.
     * 4 Last line count in file.
     * 5 Finish process flag.
     */
    clearstatcache();
    $currentSetting = [
        $fileNameWithoutExtension,
        date('Y-m-d H:i:s'),
        filesize($file),
        0,
        count($fileLineList),
        0
    ];
    $currentSettingStr=implode(",",$currentSetting);
    
    /**
     * Search for file name in index db 
     * return lineNumber and LineContent
     */
    list($line_number, $index_line_content) = searchIndexFile($ndxfile, $fileNameWithoutExtension);

    if(empty($line_number)){
        $action = "append";
        echo "Action: $action \n";
        $line_number = 0;
        $index_line_content = $currentSettingStr;
        echo "index_line_content $index_line_content \n";
        $index_line_content_new = 0;
        updateNdxFile($ndxfile, $line_number, $index_line_content, $index_line_content_new, $action, $ndxtmpfile);
    }elseif(!empty($line_number)){
        $storedSetting = explode(",", $index_line_content);
        $storedLastLineIndex = (int) $storedSetting[4];
        if($storedLastLineIndex == $currentSetting[4]){
            //logPrinter(dateLog()."Notic: The size of file '$fileNameWithoutExtension' not changed yet. \n");
            $f = @fopen("$file", "r+");
            if ($f !== false) {
                ftruncate($f, 0);
                fclose($f);
            }
            return null;
        }else{
            $action = "replace";
            echo "Action: $action \n";
            $index_line_content = $fileNameWithoutExtension ;
            echo "index_line_content $index_line_content \n";
            clearstatcache();
            $currentSetting[0] = $fileNameWithoutExtension;
            $currentSetting[1] = date('Y-m-d H:i:s');
            $currentSetting[2] = filesize($file);
            $currentSetting[3] = $storedLastLineIndex;
            $currentSetting[4] = count($fileLineList);
            $currentSetting[5] = 0;
            $index_line_content_new = implode(",",$currentSetting);
            echo "index_line_content_new $index_line_content_new \n";
            updateNdxFile($ndxfile, $line_number, $index_line_content, $index_line_content_new, $action, $ndxtmpfile);
        }
    }
    
    /**
     * Make a new temp file.
     * In case that old file exist, delete old file.
     */
    $currentReadedLineIndex = (int) $currentSetting[3];
    $currentLastLineIndex = (int) $currentSetting[4];
    $tempFilePath = $directory.$fileNameWithoutExtension.".tmp";
    if(file_exists($tempFilePath)){
        unlink($tempFilePath);
    }
    
    $startLineNumber = $currentReadedLineIndex+1;
    $endLineNumber = $currentLastLineIndex; 
    exec("cat $file | sed -n '".$startLineNumber.",".$endLineNumber."p' >> $tempFilePath");
    
    $f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }
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
        ['see_id', 'int'],
        ['subkey_code', 'int']
    ];

    /**
     * Other fields that not important in hash.
     * This feilds losted in final result, the first value for each chunk
     * Considered as final value.
     */
    $otherFields = [
        
    ];

    // Colmn name that must be added based hash.
    $columnToSum = ['count', 'int'];
    // NOTE TO ME 
    // SET MULTI COL.

    $fullFieldMap = [];

    global $cbs_see_voice_err_code, $cbs_see_name;
    $seeCode = shell_exec("stat $file | head -n 1 | cut -d \".\" -f 3");
    $seeCode = trim($seeCode);
    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_name, ['see_code', $seeCode]);
    $see_id__FIELD__ = $cbs_see_name[$index]['see_id'];
    $regionId__FIELD__ = $cbs_see_name[$index]['region_id'];

    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $4,$8,$2}' | sort -k1 -k2 -k3 -n | uniq -c");
    $uniqCDRs = explode("\n", $uniqCDRs);

    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
    
        if(count($uniqCDR_Parts) != 4){
            //echo "this line scaped for length of array error \n ";
            continue;
        }
        $count__FIELD__ = $uniqCDR_Parts[0];
        $dateTime = $uniqCDR_Parts[1];
        $ERROR_CODE__FIELD__ = $uniqCDR_Parts[2];
        $SUB_KEY = $uniqCDR_Parts[3];
       // $CLASS_CODE = $uniqCDR_Parts[4];
        // 1,2
        $subkey_code__FIELD__ = $SUB_KEY; 
        // 7
        $serrc_id = '0';
        /*$index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_voice_err_code, ['serrc_code', $ERROR_CODE]);
        // Check for find match.
        if($index == -1){
            // Not any match find, so make it and use it. 
            $serrc_id = newErrCode($ERROR_CODE);

            //echo "Make $ERROR_CODE in new error \n";
        }else{
            $serrc_id = $cbs_see_voice_err_code[$index]['serrc_id'];
        }*/
        $cdr_date = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7];
        $cdr_time = $dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11];
        $cdr_date_time__FIELD__ = $cdr_date." ".$cdr_time;

        //echo "Expected date time : $cdr_date_time \n";
        //$results[] = [$see_id, $subkey_class_id, $serrc_id, $count, $cdr_date, $cdr_time];
        // Calc log_id
        //$serrc_log_id = str_pad($see_id, 2, '0', STR_PAD_LEFT).str_pad($regionId, 2, '0', STR_PAD_LEFT).str_pad($serrc_id, 2, '0', STR_PAD_LEFT).str_pad($subkey_code, 2, '0', STR_PAD_LEFT).str_pad(strtotime($cdr_date_time), 10, '0', STR_PAD_LEFT);
        //echo "Expected primary key: $serrc_log_id \n";

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
define('FEED_LOG_DIR', "/cbshome/cdr_analysis/data/cbs/see/voice/tehran");

//$city = getDirList(FEED_LOG_DIR);
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

            /**
             * Control sub-directories that must be needed in each directory.
             */
            $tempFileDirectoryPath = FEED_LOG_DIR."/"."temp-files/";
            $logFileDirectoryPath = FEED_LOG_DIR."/"."logs/";
            $ndxFilePath = FEED_LOG_DIR."/"."indexdb.ndx";
            $ndxtmpFilePath = FEED_LOG_DIR."/"."indexdb.tmp";

            clearstatcache();
            if(!is_dir($tempFileDirectoryPath)){
                mkdir($tempFileDirectoryPath);
            }
            if(!is_dir($logFileDirectoryPath)){
                mkdir($logFileDirectoryPath);
            }
            if(!is_file($ndxFilePath)){
                touch($ndxFilePath);
                echo "ndxFile: $ndxFilePath \n";
            }
            if(!is_file($ndxtmpFilePath)){
                touch($ndxtmpFilePath);
                echo "ndxtmpFile: $ndxtmpFilePath \n\n\n";
            }


            /**
             * Iterate on files.
             */
            foreach($fileList as $file){
                // Make indexes and more detail inside the function.
                $file = updateIndex($file, $tempFileDirectoryPath ,$ndxFilePath ,$ndxtmpFilePath);

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

                //remove temp file
                unlink($file);

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
                    ['cdr_date_time', 'cdr_date_time'], 
                    ['serrc_code', 'ERROR_CODE'], 
                    ['region_id', 'regionId'], 
                    ['see_id', 'see_id'],
                    ['subkey_code', 'subkey_code'],
                    ['e_count', 'count'],
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
                $sql = "INSERT INTO cbs_see_voice_err_code_log VALUES $insertTupleCSV 
                ON CONFLICT ON CONSTRAINT cbs_see_voice_err_code_log_uniq_cons DO UPDATE
                SET e_count = cbs_see_voice_err_code_log.e_count+excluded.e_count;
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
        }
//    }
sleep(1);
}