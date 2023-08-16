<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */
require_once "config_kpi.php";

// Cache talbles.


// end cache.


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
ini_set('memory_limit','8192M');
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
    $last_hour_timestamp = $timestamp-86400;
    $files = scandir($path);   

    foreach($files as $file){
        $fullPath = $path."/".$file;
        if(is_file($fullPath) && explode(".", $fullPath)[count(explode(".", $fullPath))-1] == "gz"){
            $fileDate= filectime($fullPath);
            if($file == "." || $file == '..'){continue;}
            if (filesize($fullPath) == 0 ){continue;}
            if ($fileDate < $last_hour_timestamp){continue;}
            if((substr(explode("_",$file)[2],0,3) != "138") && (substr(explode("_",$file)[2],0,2) == "13") && (explode("_",$file)[3] == "30")){
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
        echo "start appending file\n";
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
    
    //if(file_exists($file)){
    //    unlink($file);
    //}
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
 * @return array an insertable array to database, [cbp_id, cerrc_id, count, cdr_date, cdr_time]
 */

function coreKpiInserter($file){
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
        ['date_time', 'string', 'date_time'],
        ['interval', 'string', 'interval'],
        ['counter', 'string', 'counter'],
        ['node','string','node'],
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
    
    //unzip the file and zero origin file
    $fileCSV = $file;
    //echo "csvfile: $fileCSV \n";
    //shell_exec("gunzip $file");
    /*$f = @fopen("$file", "r+");
            if ($f !== false) {
                ftruncate($f, 0);
                fclose($f);
            }*/

    
    echo "$file\n";
    $start3 = microtime(true);
    $uniqCDRs = shell_exec(" cat $fileCSV | awk -vFPAT='([^,]*)|(\"[^\"]+\")' -vOFS=, '{gsub(\",\",\"\",$3);gsub(\",\",\"|\");print $0}' ");
    $finish_second3 = (microtime(true)-$start3)*1000;
    echo "awk Time--> $finish_second3\n";

            
    $uniqCDRs = explode("\n", $uniqCDRs);
    $countUniqCDRs = count($uniqCDRs);

    $headfiles = explode("|", $uniqCDRs[0]);
    $countColfile = count($headfiles);


    // an array to hold results.
    $results = [];
    foreach($headfiles as $headfile){
        $counterKey= array_search($headfile,$headfiles);
        
        $headfile = trim($headfile,"\"");
        $headfile = intval($headfile);
       

        if(is_int($headfile) && $headfile>0){  
            
            $rowNum = 1;
            foreach($uniqCDRs as $uniqCDR){
                if($rowNum <= 2){$rowNum+=1;continue;}
                $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
                $uniqCDR_Parts = explode("|", $uniqCDR);
                $countUniqCDR_Parts = count($uniqCDR_Parts);
                if($countUniqCDR_Parts =! $countColfile){continue;}
                if(empty($uniqCDR_Parts[0])){continue;}
               
                $date_time__FIELD__ = $uniqCDR_Parts[0].":"."00";
                $interval__FIELD__ = $uniqCDR_Parts[1];
                $counter__FIELD__ = $headfile;
                $node__FIELD__ = $uniqCDR_Parts[2];
                $node__FIELD__ = trim($node__FIELD__,"\"");
                //$node__FIELD__ = explode('/',$node__FIELD__);
                //$node__FIELD__ = $node__FIELD__[0];
                
                if(isset($uniqCDR_Parts[$counterKey])){
                    $e_count__FIELD__ = $uniqCDR_Parts[$counterKey];
                    if(!is_numeric($e_count__FIELD__)){
                        //echo "\n";
                        //echo "file CSV: $fileCSV\n";
                        //echo "e_count not numeric: $e_count__FIELD__ \n\n\n";
                        $e_count__FIELD__ = "0";
                    }
                }else{
                    //echo "\n\n";
                    //echo "file CSV: $fileCSV\n";
                    $e_count__FIELD__ = "0";
                }
            
                //echo "date_time: $date_time__FIELD__\n";
                //echo "interval: $interval__FIELD__\n";
                //echo "counter: $counter__FIELD__ \n";
                //echo "node: $node__FIELD__\n";
                //echo "e_count: $e_count__FIELD__\n";
                
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
        }
    }

    //$start = microtime(true);
    $final_result = new stdClass();
    $final_result->result = $results;
    $final_result->countableFieldMap = $countableFieldMap;
    $final_result->colsMap = $colsMap;
    $final_result->uniqKeyMap = $uniqKeyMap;
    //$finish_milsecond = (microtime(true)-$start)*1000;
    //echo "cellInserterFuncStartPart2: $finish_milsecond\n\n";
    //print_r($final_result);
    //echo '\n\n\n\n';
    
    return $final_result;
}
// End of requirements for inserter.

/**
 * Directory address that holds log files,
 * Database feeded from this directory to make data.
 */
//define('FEED_LOG_DIR', "/home/cbshome/failedcdr_analysis/data");
$dataDir = getenv("INS_DATA_DIR");
define('FEED_LOG_DIR', "$dataDir");

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
            /*
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

                //echo "csvfile: $fileCSV \n";
                shell_exec("gunzip $file"); 
                //$f = @fopen("$file", "r+");
                //if ($f !== false) {
                //    ftruncate($f, 0);
                //    fclose($f);
                //}
                shell_exec("truncate -s0 $file");
                $file = trim($file,".gz");
                
                // Make indexes and more detail inside the function.
                //$file = updateIndex($file, $tempFileDirectoryPath ,$ndxFilePath ,$ndxtmpFilePath);
                
                // NOTE TO ME
                // REMOVE CONTROL OF PROCEESSING.
                
                /**
                 * Ignore in case that file processing is not finished 
                 * Or file size not changed.
                 */
                //if($file == null){
                //    continue;
                //}
                //$fileName = removePathFromFilePath($file);
                //$fileNamePure = removeFileExtension($fileName);
                //$logFilePath = $logFileDirectoryPath.$fileNamePure.".log";
                //$indexFilePath = $tempFileDirectoryPath.$fileNamePure.".ndx";
                //$fileTempPath = $tempFileDirectoryPath.$fileNamePure.".tmp";
                //logPrinter("\n".dateLog()."Feed file: $fileNamePure \n");
                if(count(file($file))> "100000"){continue;}
                $fileProcessedRows = coreKpiInserter($file);
                
                unlink($file);
                logPrinter(dateLog()."Row conut in file: ".count($fileProcessedRows->result)."\n");
                if(count($fileProcessedRows->result)> "100000"){continue;}
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
                        $statementsToUpdateOnConflict .= "$itemFieldIndex = excluded.$itemFieldIndex,";
                    }    
                }

                // Remove last comma char. 
                $columnNamesCSV = rtrim($columnNamesCSV, ",");
                $columnNamesUniqKeyCSV = rtrim($columnNamesUniqKeyCSV, ",");
                $statementsToUpdateOnConflict = rtrim($statementsToUpdateOnConflict, ",");

                // Make final sql command.
                $sql = "INSERT INTO mci_core_kpi_log ($columnNamesCSV) VALUES $insertCSV 
                        ON CONFLICT ($columnNamesUniqKeyCSV) DO UPDATE
                        SET $statementsToUpdateOnConflict;";
               
                
            
                // Execute sql command and check status of it.
                $start = microtime(true);
                $queryStatus = query($sql)->status;
                if(!$queryStatus){
                    logPrinter(dateLog()."Error in query!\n");
                }

            $finish_second = (microtime(true)-$start)*1000;
            echo "query Time: $finish_second\n";

            $finish_second1 = (microtime(true)-$start1)*1000;
            echo "TotalTime--> $finish_second1\n\n";
            sleep(1);
           
            }
        }
//    }
sleep(1);
}