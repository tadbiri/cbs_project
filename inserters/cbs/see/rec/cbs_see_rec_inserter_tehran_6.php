<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

// Cache talbles.
/**
 * msc_termination_reason_code
 * msc_termination_reason_id
 */
$cbs_see_rec_msc_termination_reason = query("SELECT * FROM cbs_see_rec_msc_termination_reason ")->result;

/**
 * msc_address
 * msc_id
 */
$cbs_see_rec_msc_address = query("SELECT msc_id,msc_address  FROM cbs_see_rec_msc_address ")->result;

/**
 * msc_call_type_code
 * msc_call_type_id
 */
//$cbs_see_rec_msc_call_type = query("SELECT * FROM cbs_see_rec_msc_call_type")->result;

/**
 * see_id
 * see_code
 */
$cbs_see_name = query("SELECT * FROM cbs_see_name")->result;

$cbs_cell_map = query("SELECT city_code,province_code FROM cbs_cell_map")->result;

///////////////////////////////////////////////////// end cache.


function newTerminationReason ($terminationReasonCode){
    $terminationReasonDesc = 'new-item';
    $query = "INSERT INTO cbs_see_rec_msc_termination_reason ( termination_reason_code, termination_reason_desc) VALUES (?, ?)";
    return query($query, [$terminationReasonCode, $terminationReasonDesc]);
}

function newCallType ($callTypeCode){
    $callTypeDesc = "new-item";
    $query = "INSERT INTO cbs_see_rec_msc_call_type (call_type_code, call_type_desc) VALUES (?, ?)";
    return query($query, [$callTypeCode, $callTypeDesc]);
}

function newMscAddress ($mscAddress){
    /*if $mscAddress == "964"{
        $mscCode = "new-item";
        $mscName = "new-item";
        $vendor = "iraq";
    }*/
    $query = "INSERT INTO cbs_see_rec_msc_address (msc_code, msc_name, msc_address, vendor) VALUES (?, ?, ?, ?)";
    if (preg_match("/^96475.*/",$mscAddress) == 1){
        $mscCode = "Korektcell";
        $mscName = "Korektcell";
        $vendor = "iraq";
        return query($query, [$mscCode, $mscName, $mscAddress, $vendor]);
    }

    if (preg_match("/^96477.*/",$mscAddress) == 1){
        $mscCode = "Asiacell";
        $mscName = "Asiacell";
        $vendor = "iraq";
        return query($query, [$mscCode, $mscName, $mscAddress, $vendor]);
    }

    if (preg_match("/^96478.*/",$mscAddress) == 1){
        $mscCode = "Zein";
        $mscName = "Zein";
        $vendor = "iraq";
        return query($query, [$mscCode, $mscName, $mscAddress, $vendor]);
    }
    return;
}
///////////////////////////////////////////////////// end .

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
        if(is_file($fullPath) && explode(".", $fullPath)[count(explode(".", $fullPath))-1] == "unl" && ( explode(".", $fullPath)[2] == "01006") ){
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
    global $cbs_see_rec_msc_termination_reason, $cbs_see_name;
    $seeCode = shell_exec("stat $file | head -n 1 | cut -d \".\" -f 3");
    $seeCode = trim($seeCode);
    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_name, ['see_code', $seeCode]);
    $see_id = $cbs_see_name[$index]['see_id'];
    $regionId = $cbs_see_name[$index]['region_id'];

    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $29,$76}' | sed 's/.//14' |  sed 's/.//13' | sort -k1 -k2 -n | uniq -c");
    $uniqCDRs = explode("\n", $uniqCDRs);

    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 3){continue;}
        $count = $uniqCDR_Parts[0];
        $dateTime = $uniqCDR_Parts[1];
        $ERROR_CODE = $uniqCDR_Parts[2];
        
        // Termination Reason
        $termination_reason_id = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_termination_reason, ['termination_reason_code', $ERROR_CODE]);
        // Check for find match.
        if($index == -1){
            $cbs_see_rec_msc_termination_reason = query("SELECT * FROM cbs_see_rec_msc_termination_reason ");
            $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_termination_reason, ['termination_reason_code', $ERROR_CODE]);
            if($index == -1){
            // Not any match find, so make it and use it. 
            $termination_reason_id = newTerminationReason($ERROR_CODE);
            }else{
                $termination_reason_id = $cbs_see_rec_msc_termination_reason[$index]['termination_reason_id'];
            }
        }else{
            $termination_reason_id = $cbs_see_rec_msc_termination_reason[$index]['termination_reason_id'];
        }
        $cdr_date = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7];
        $cdr_time = $dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11];
        $cdr_date_time = $cdr_date." ".$cdr_time;
     
        // Calc log_id
        $serrc_log_id = str_pad($see_id, 2, '0', STR_PAD_LEFT).str_pad($regionId, 2, '0', STR_PAD_LEFT).str_pad($termination_reason_id, 2, '0', STR_PAD_LEFT).str_pad(strtotime($cdr_date_time), 10, '0', STR_PAD_LEFT);
        $results[] = [$serrc_log_id, $cdr_date_time, $termination_reason_id, $regionId, $see_id, $count];
        
        
    }
    // Clear content of file after calc.
    /*
    $f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }*/
    return $results;
}

/**
 * get file path and get sql command for it.
 * 
 * @param string $file path of file to feed.
 * @return array an insertable array to database, [see_id, subkey_class_id, serrc_id, count, cdr_date, cdr_time]
 */
function seeRecMSCInserter($file){
    global $cbs_cell_map;
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
        ['cdr_date_time', 'string', 'cdr_date_time'],
        ['msc_id', 'int', 'msc_id'],
        ['province_code', 'string', 'province_code'],
        ['termination_reason_id', 'int', 'termination_reason_id'],
        ['count','int','e_count'],
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
    
    global $cbs_see_rec_msc_termination_reason, $cbs_see_rec_msc_address;
    /*$seeCode = shell_exec("stat $file | head -n 1 | cut -d \".\" -f 3");
    $seeCode = trim($seeCode);
    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_name, ['see_code', $seeCode]);
    $regionId = $cbs_see_name[$index]['region_id'];*/
    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $29,$76,$27,$15,$81}' | sort -k1 -k2 -k3 -k4 -k5  -n | uniq -c");
    $uniqCDRs = explode("\n", $uniqCDRs);

    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 6){continue;}
        $count__FIELD__ = $uniqCDR_Parts[0];
        $dateTime = $uniqCDR_Parts[1];
        $TERM_CODE = $uniqCDR_Parts[2];
        $MSC_ADD = $uniqCDR_Parts[3];
        $cell_code = $uniqCDR_Parts[4];
        
        if($uniqCDR_Parts[5] == '98'){
            $city_code = substr($cell_code,7,2);
                $index1 = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cell_map, ['city_code', $city_code]);
                if($index1 != -1){
                    $province_code__FIELD__ = $cbs_cell_map[$index1]['province_code'];
                }else{
                    $province_code__FIELD__ = "0000";
                    
                }
                    
        }else{
            $province_code__FIELD__ = 'r'.$uniqCDR_Parts[5];
            
        }


        // MSC_Address
        $msc_id__FIELD__ = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_address, ['msc_address', $MSC_ADD]);
        // Check for find match.
        if($index == -1){
            continue;
            //echo "OtherMSC: $MSC_ADD \n";
            $cbs_see_rec_msc_address = query("SELECT msc_id,msc_address  FROM cbs_see_rec_msc_address ");
            $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_address, ['msc_address', $MSC_ADD]);
            if($index == -1){
            $msc_id__FIELD__ = newMscAddress($MSC_ADD);
            }else{
            $msc_id__FIELD__ = $cbs_see_rec_msc_address[$index]['msc_id'];
            }
        }else{
            $msc_id__FIELD__ = $cbs_see_rec_msc_address[$index]['msc_id'];
        }

        // Termination Reason
        $termination_reason_id__FIELD__ = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_termination_reason, ['termination_reason_code', $TERM_CODE]);
        // Check for find match.
        if($index == -1){
            $cbs_see_rec_msc_termination_reason = query("SELECT * FROM cbs_see_rec_msc_termination_reason ");
            $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_termination_reason, ['termination_reason_code', $TERM_CODE]);
            if($index == -1){
            // Not any match find, so make it and use it. 
            $termination_reason_id__FIELD__ = newTerminationReason($TERM_CODE);
            }else{
                $termination_reason_id__FIELD__ = $cbs_see_rec_msc_termination_reason[$index]['termination_reason_id'];
            }
        }else{
            $termination_reason_id__FIELD__ = $cbs_see_rec_msc_termination_reason[$index]['termination_reason_id'];
        }

        $cdr_date = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7];
        $cdr_time = $dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11];
        $cdr_date_time__FIELD__ = $cdr_date." ".$cdr_time;
        
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
define('FEED_LOG_DIR', "/cbshome/cdr_analysis/data/cbs/see/rec/tehran");


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
                $start1 = microtime(true);
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
                $fileTempPath = $tempFileDirectoryPath.$fileNamePure.".tmp";

                logPrinter("\n".dateLog()."Feed file: $fileNamePure \n");

                $fileProcessedRows = seeRecMSCInserter($fileTempPath);
                logPrinter(dateLog()."Row conut in file: ".count($fileProcessedRows->result)."\n");
                
                unlink($fileTempPath);

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
                        $statementsToUpdateOnConflict .= "$itemFieldIndex = cbs_see_rec_msc_log.$itemFieldIndex+excluded.$itemFieldIndex,";
                    }    
                }

                // Remove last comma char. 
                $columnNamesCSV = rtrim($columnNamesCSV, ",");
                $columnNamesUniqKeyCSV = rtrim($columnNamesUniqKeyCSV, ",");
                $statementsToUpdateOnConflict = rtrim($statementsToUpdateOnConflict, ",");

                // Make final sql command.
                $sql = "INSERT INTO cbs_see_rec_msc_log ($columnNamesCSV) VALUES $insertCSV 
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
            echo "queryinsert1: $finish_second\n";

            $finish_second1 = (microtime(true)-$start1)*1000;
            echo "TotalTime--> $finish_second1\n";

            
            }
        }
//  }
sleep(1);
}
