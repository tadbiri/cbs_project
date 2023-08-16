<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

/**
 * lac_id
 * cell_code
 * cbs_cell_code
 */
$cbs_cell_info = query("SELECT * FROM cbs_cell_info")->result;

function newCell ($lac_id, $cell_code, $cbs_cell_code){
    $query = "INSERT INTO cbs_cell_info (lac_id, cell_code,cbs_cell_code) VALUES ($lac_id, $cell_code, $cbs_cell_code)";
    return query($query);
}

/**
 * msc_address
 * city_code
 */
$cbs_see_rec_msc_address = query("SELECT msc_address,area_code FROM cbs_see_rec_msc_address")->result;


/**
 * province_code
 * city_code
 */
$cbs_cell_map = query("SELECT city_code,province_code FROM cbs_cell_map")->result;

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
        if(substr($file,0, 4) === "orec"){
            if(is_file($fullPath) && explode(".", $fullPath)[count(explode(".", $fullPath))-1] == "unl"){
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
    $filename2 = $file.'.sec';
    copy($file,$filename2);
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





//cell id 

 function cbs_cbp_rec_cell_inserter($file){
    //$start = microtime(true);
     global $cbs_cell_info,$cbs_see_rec_msc_address,$cbs_cell_map;
    /**
     * In here data structure on this method and relation of it with database must be described.
     * Each row have this struct: 
     * [FIELD-NAME-ON-METHOD, DATA-TYPE, FIELD-NAME-ON-DB] 
     * 
     * All FIELD-NAME-ON-METHOD field must have postfix like this. __FIELD__.
     */
    $colsMap = [
        ['cdr_date_time', 'string', 'cdr_date_time'],
        ['cell_code', 'string', 'cell_code'],
        ['area_code', 'string', 'area_code'],
        ['service_type','string','service_type'],
        ['usage_service_type','int','usage_service_type'],
        ['call_min', 'int', 'call_min'],
        ['call_debit_amount', 'int', 'call_debit_amount'],
        ['call_free_amount', 'int', 'call_free_amount'],
        ['call_min_free', 'int', 'call_min_free'],
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
    $uniqKeyMap = [0,1,2,3,4];
    $countableFieldMap = [5,6,7,8];
    global $cbs_cbp_cellid;
    
    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{ print $13,$35,$42+$49,$560,$501,$51,$514,$498,$19,$524,$526,$527,$529}'");   

    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    
    //$finish_milsecond = (microtime(true)-$start)*1000;
    //echo "cellInserterFuncStartawk: $finish_milsecond\n\n";

    foreach($uniqCDRs as $uniqCDR){
        //$start = microtime(true);
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        
        if(count($uniqCDR_Parts) != 13){continue;}
        $roam_country_code = $uniqCDR_Parts[9];
        $roam_net_code = $uniqCDR_Parts[10];

        $dateTime = $uniqCDR_Parts[0];
        $call_min__FIELD__ = $uniqCDR_Parts[1];

        $call_debit_amount = $uniqCDR_Parts[2];
        if(empty($call_debit_amount)){
            $call_debit_amount__FIELD__ ='0';
        }else{
            $call_debit_amount_left = substr($call_debit_amount,0,-3);
            $call_debit_amount_right = substr($call_debit_amount,-3);
            $call_debit_amount__FIELD__ = $call_debit_amount_left.".".$call_debit_amount_right;
        }

        $call_free_amount = $uniqCDR_Parts[3];
        if(empty($call_free_amount)){
            $call_free_amount__FIELD__ ='0';
        }else{
            $call_free_amount_left = substr($call_free_amount,0,-3);
            $call_free_amount_right = substr($call_free_amount,-3);
            $call_free_amount__FIELD__ = $call_free_amount_left.".".$call_free_amount_right;
        }
        $call_min_free__FIELD__ = $uniqCDR_Parts[5];
        if(empty($call_min_free__FIELD__)){
            $call_min_free__FIELD__ ='0';
        }
                   
    
        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*3.5);
        $cdr_date_time__FIELD__ =  date('Y-m-d H:i', $cdr_date_time);

        $roam_country_code = $uniqCDR_Parts[9];
        $roam_net_code = $uniqCDR_Parts[10];
        $msc_address = $uniqCDR_Parts[6];

        if($roam_country_code == 98){
            if($roam_net_code == 1){
                //iran mci network
                $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_address, ['msc_address', $msc_address]);
                // Check for find match.
                if($index == -1){
                    //echo "msc not found \n";
                    $area_code__FIELD__ = '98-1-noMSC';
                    $cell_code__FIELD__ = '98-1-noMSC';
                }else{
                    $area_code = $cbs_see_rec_msc_address[$index]['area_code'];
                    $cell_code__FIELD__ = $uniqCDR_Parts[4];
                    
                    if($area_code == '8888'){
                        $city_code = substr($cell_code__FIELD__,7,2);
                        $index1 = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cell_map, ['city_code', $city_code]);
                        if($index1 != -1){
                            $area_code__FIELD__ = $cbs_cell_map[$index1]['province_code'];
                            $cell_code__FIELD__ = $uniqCDR_Parts[4].'i';
                        }else{
                            $area_code__FIELD__ = '0000i';
                            $cell_code__FIELD__ = $uniqCDR_Parts[4];
                            if(empty($cell_code__FIELD__)){
                                $cell_code__FIELD__ = '0000i';
                            }
                        }
                    }else{
                        $city_code = substr($cell_code__FIELD__,7,2);
                        $index1 = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cell_map, ['city_code', $city_code]);
                        if($index1 != -1){
                            $area_code__FIELD__ = $cbs_cell_map[$index1]['province_code'];
                        }else{
                            $area_code__FIELD__ = "0000";
                            if(empty($cell_code__FIELD__)){
                                $cell_code__FIELD__ = '0000';
                            }
                        }
                    }
                }
            }else{
                //iran not mci network
                $area_code__FIELD__ = "r".$roam_net_code;
                $cell_code__FIELD__ = $msc_address;
                if(empty($cell_code__FIELD__)){
                    $cell_code__FIELD__ ='0';
                }
            }
        }else{
            //not iran network
            $area_code__FIELD__ = "r".$roam_country_code;
            $cell_code__FIELD__ = 'r9999'; 
        }
        
        $service_type__FIELD__ = $uniqCDR_Parts[7];
        $called_country_code = $uniqCDR_Parts[11];
        $called_net_code = $uniqCDR_Parts[12];

        if($called_country_code == 98){
            $service_type__FIELD__ = $called_net_code;
            if(empty($service_type__FIELD__)){
                $service_type__FIELD__ ='0';
            }
        }else{
            $service_type__FIELD__ ="r".$called_country_code;
            if(empty($service_type__FIELD__)){
                $service_type__FIELD__ ='0';
            }
        }
        

        //usage_service_type
        $usage_service_type__FIELD__ = $uniqCDR_Parts[8];
        if(empty($usage_service_type__FIELD__)){
            $usage_service_type__FIELD__ ='0';
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
                $start = microtime(true);
                // Make indexes and more detail inside the function.
                $file = updateIndex($file, $tempFileDirectoryPath ,$ndxFilePath ,$ndxtmpFilePath);
                $finish_second = (microtime(true)-$start)*1000;
                echo "updateIndexTime: $finish_second\n";
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

                
            
            #################################### cbs_cbp_rec_cell_inserter ##################################################

                //logPrinter("\n".dateLog()."Feed file: $fileNamePure \n");
                $start = microtime(true);
                $fileProcessedRows = cbs_cbp_rec_cell_inserter($fileTempPath);
                $finish_second = (microtime(true)-$start)*1000;
                echo "inserterCelltime: $finish_second\n";

                //logPrinter(dateLog()."Row conut in file: ".count($fileProcessedRows->result)."\n");
                $start = microtime(true);
                //remove temp file
                unlink($fileTempPath);

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
                        $statementsToUpdateOnConflict .= "$itemFieldIndex = cbs_cbp_rec_cell_log.$itemFieldIndex+excluded.$itemFieldIndex,";
                    }    
                }

                // Remove last comma char. 
                $columnNamesCSV = rtrim($columnNamesCSV, ",");
                $columnNamesUniqKeyCSV = rtrim($columnNamesUniqKeyCSV, ",");
                $statementsToUpdateOnConflict = rtrim($statementsToUpdateOnConflict, ",");

                // Make final sql command.
                $sql = "INSERT INTO cbs_cbp_rec_cell_log ($columnNamesCSV) VALUES $insertCSV 
                        ON CONFLICT ($columnNamesUniqKeyCSV) DO UPDATE
                        SET $statementsToUpdateOnConflict;";
                
                $finish_second = (microtime(true)-$start)*1000;
                echo "mapBuildQuery: $finish_second\n";
                //print_r($sql);
                //echo "\n\n\n\n";    
                // Execute sql command and check status of it.
                $start = microtime(true);
                $queryStatus = query($sql)->status;
                if(!$queryStatus){
                    logPrinter(dateLog()."Error in query!\n");
                }

            $finish_second = (microtime(true)-$start)*1000;
            echo "queryinserttime: $finish_second\n";

            $finish_second1 = (microtime(true)-$start1)*1000;
            echo "TotalTime--> $finish_second1\n\n";
            }
        }
//    }
sleep(1);
}

