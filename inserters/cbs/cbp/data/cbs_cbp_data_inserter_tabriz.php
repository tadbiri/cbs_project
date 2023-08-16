<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

// Cache talbles.

/**
 * cbp_id
 * cbp_code
 */
$cbs_cbp_name = query("SELECT * FROM cbs_cbp_name")->result;

/**
 * cerrc_id
 * cerrc_code
 */
$cbs_cbp_data_err_code = query("SELECT cerrc_id, cerrc_code FROM cbs_cbp_data_err_code")->result;

/**
 * rg_id
 * rg_code
 */
$cbs_cbp_data_rg_code = query("SELECT rg_id, rg_code FROM cbs_cbp_data_rg_code")->result;


/**
 * province_code
 * city_code
 */
$cbs_cell_map = query("SELECT city_code,province_code FROM cbs_cell_map")->result;

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

function insertFailedCDR($file){
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
        ['cerrc_id', 'int', 'cerrc_id'],
        ['regionId', 'int', 'region_id'],
        ['cbp_id','int','cbp_id'],
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
    
    global $cbs_cbp_data_rg_code, $cbs_cbp_data_err_code, $cbs_cbp_name;
    $cbpCode = shell_exec("stat $file | head -n1 | cut -d \"_\" -f 3");
    //echo "cbp_code: $cbpCode \n";
    $cbpCode = trim($cbpCode);
    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_name, ['cbp_code', $cbpCode]);
    //echo "index_cbp_code: $index \n";
    $cbp_id__FIELD__ = $cbs_cbp_name[$index]['cbp_id'];
    //echo "cbp_id__FIELD__: $cbp_id__FIELD__ \n";
    $regionId__FIELD__ = $cbs_cbp_name[$index]['region_id'];
    //echo "regionId__FIELD__: $regionId__FIELD__ \n";

    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $13,$29}' | sort -k1 -k2 -n | uniq -c");
    $uniqCDRs = explode("\n", $uniqCDRs);
    
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 3){continue;}
        $e_count__FIELD__ = $uniqCDR_Parts[0];
        $dateTime = $uniqCDR_Parts[1];
        $ERROR_CODE = $uniqCDR_Parts[2];
        // Error code id
        $cerrc_id__FIELD__ = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_data_err_code, ['cerrc_code', $ERROR_CODE]);
        // Check for find match.
        if($index == -1){
            $cbs_cbp_data_err_code = query("SELECT cerrc_id, cerrc_code FROM cbs_cbp_data_err_code")->result;
            $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_data_err_code, ['cerrc_code', $ERROR_CODE]);
            if($index == -1){
            // Not any match find, so make it and use it. 
            $cerrc_id__FIELD__ = newErrCode($ERROR_CODE);
            }else{
                $cerrc_id__FIELD__ = $cbs_cbp_data_err_code[$index]['cerrc_id'];
            }
        }else{
            $cerrc_id__FIELD__ = $cbs_cbp_data_err_code[$index]['cerrc_id'];
        }
        
        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*3.5);
        $cdr_date_time__FIELD__ =  date('Y-m-d H:i', $cdr_date_time);

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

function roaminserter($file){
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
   $uniqKeyMap = [0];
   $countableFieldMap = [1];

   $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{if($523 == \"966\") print $13,$35+$39,$42,$544}'");
   //echo "awk: $uniqCDRs \n";
   $uniqCDRs = explode("\n", $uniqCDRs);
   //print_r($uniqCDRs);
   //echo "\n\n\n";
   // an array to hold results.
   $results = [];
   foreach($uniqCDRs as $uniqCDR){
       $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
       $uniqCDR_Parts = explode(" ", $uniqCDR);
       if(count($uniqCDR_Parts) != 3){continue;}
       $dateTime = $uniqCDR_Parts[0];
       $actual_usage = $uniqCDR_Parts[1];
       $debit_amount = $uniqCDR_Parts[2];
       $pkg_id = $uniqCDR_Parts[3];
       if(!isset($uniqCDR_Parts[3])){
        $pkg_id = 0;
       }
       //echo "pkg_id: $pkg_id \n\n\n";
       if(!isset($uniqCDR_Parts[1])){
        $actual_usage = 0;
       }
       if(!isset($uniqCDR_Parts[2])){
        $debit_amount = 0;
       }

       if($pkg_id == 0 && $debit_amount != 0 ){
        //echo "pkg_id_in: $pkg_id \n\n\n";   
        $actual_usage__FIELD__ = $actual_usage;
       }else{continue;}
       
       $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
       $cdr_date_time = strtotime("$cdr_date_time");
       $cdr_date_time = $cdr_date_time+(60*60*3.5);
       $cdr_date_time__FIELD__ =  date('Y-m-d H:i', $cdr_date_time);

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

function cbs_cbp_data_cell_inserter($file){
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
        ['cell_code', 'string', 'cell_code'],
        ['city_code', 'string', 'city_code'],
        ['rat_type','int','rat_type'],
        ['service_usage_type','int','service_usage_type'],
        ['rg', 'int', 'rg'],
        ['ugw_address', 'string', 'ugw_address'],
        ['actual_usage', 'int', 'actual_usage'],
        ['debit_amount', 'int', 'debit_amount'],
        ['free_amount', 'int', 'free_amount']
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
    $uniqKeyMap = [0,1,2,3,4,5,6];
    $countableFieldMap = [7,8,9];
    
    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{ print $13,$499,$533,$19,$40+$35,$42+$49,$542,$525,$535,$497,$523}'");   

    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    
    //$finish_milsecond = (microtime(true)-$start)*1000;
    //echo "cellInserterFuncStartawk: $finish_milsecond\n\n";

    foreach($uniqCDRs as $uniqCDR){
        //$start = microtime(true);
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        
        if(count($uniqCDR_Parts) != 11){continue;}
        
        $dateTime = $uniqCDR_Parts[0];

        $cell_code__FIELD__ = $uniqCDR_Parts[1];

        $rat_type__FIELD__ = $uniqCDR_Parts[2];

        $roam_country_code = $uniqCDR_Parts[10];

        $roam_net_code = $uniqCDR_Parts[7];
        if($roam_net_code == 1){
            if(strlen($cell_code__FIELD__) == '15'){
                $city_code__FIELD__ = substr($cell_code__FIELD__,7,2);
                $index1 = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cell_map, ['city_code', $city_code__FIELD__]);
                if($index1 == -1){
                    $city_code__FIELD__ = '0'; 
                }else{
                    $city_code__FIELD__ = $cbs_cell_map[$index1]['province_code'];
                }
            }elseif(strlen($cell_code__FIELD__) > '15'){
                //echo "cellCode: $cell_code__FIELD__\n";
                $city_code__FIELD__ = substr($cell_code__FIELD__,5,4);
                //echo "spilitCell: $city_code__FIELD__\n";
                $city_code__FIELD__ = hexdec("'".$city_code__FIELD__."'");
                //echo "hextodecCell: $city_code__FIELD__\n";
    
                if(strlen($city_code__FIELD__) == 4){
                    $city_code__FIELD__ = substr($city_code__FIELD__,1,2);
                }elseif(strlen($city_code__FIELD__) == 3){
                    $city_code__FIELD__ = substr($city_code__FIELD__,0,2);
                }else{
                    $city_code__FIELD__ = substr($city_code__FIELD__,2,2);
                }
                $index1 = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cell_map, ['city_code', $city_code__FIELD__]);
                if($index1 == -1){
                    $city_code__FIELD__ = '0'; 
                }else{
                    $city_code__FIELD__ = $cbs_cell_map[$index1]['province_code'];
                }
            }elseif(empty($cell_code__FIELD__)){
                $city_code__FIELD__ = '0';
            }
        }elseif($roam_net_code == 9999) {
            $city_code__FIELD__ = "r".$roam_country_code;    
        }else{
            $city_code__FIELD__ = "r".$roam_net_code;
        }
        
        
        $service_usage_type__FIELD__ = $uniqCDR_Parts[3];
        if(empty($service_usage_type__FIELD__)){
            $service_usage_type__FIELD__ ='0';
        } 

        $actual_usage__FIELD__ = $uniqCDR_Parts[4];
        if(empty($actual_usage__FIELD__)){
            $actual_usage__FIELD__ ='0';
        }

        $debit_amount = $uniqCDR_Parts[5];
        if(empty($debit_amount)){
            $debit_amount__FIELD__ ='0';
        }else{
            $debit_amount_left = substr($debit_amount,0,-3);
            $debit_amount_right = substr($debit_amount,-3);
            $debit_amount__FIELD__ = $debit_amount_left.".".$debit_amount_right;
        }

        $free_amount = $uniqCDR_Parts[6];
        if(empty($free_amount)){
            $free_amount__FIELD__ ='0';
        }else{
            $free_amount_left = substr($free_amount,0,-3);
            $free_amount_right = substr($free_amount,-3);
            $free_amount__FIELD__ = $free_amount_left.".".$free_amount_right;
        }
        
        $rg__FIELD__= $uniqCDR_Parts[8];
        $ugw_address__FIELD__ = $uniqCDR_Parts[9];

        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*3.5);
        $cdr_date_time__FIELD__ =  date('Y-m-d H:i', $cdr_date_time);
        
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
 * Directory address that holds log files,
 * Database feeded from this directory to make data.
 */
//define('FEED_LOG_DIR', "/home/cbshome/failedcdr_analysis/data");
define('FEED_LOG_DIR', "/cbshome/cdr_analysis/data/cbs/cbp/data/tabriz");

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

                $fileProcessedRows = insertFailedCDR($fileTempPath);
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
                        $statementsToUpdateOnConflict .= "$itemFieldIndex = cbs_cbp_data_err_code_log.$itemFieldIndex+excluded.$itemFieldIndex,";
                    }    
                }

                // Remove last comma char. 
                $columnNamesCSV = rtrim($columnNamesCSV, ",");
                $columnNamesUniqKeyCSV = rtrim($columnNamesUniqKeyCSV, ",");
                $statementsToUpdateOnConflict = rtrim($statementsToUpdateOnConflict, ",");

                // Make final sql command.
                $sql = "INSERT INTO cbs_cbp_data_err_code_log ($columnNamesCSV) VALUES $insertCSV 
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
            
            ///////////////////////////////// cbs_cbp_data_roam_payg_inserter //////////////////////////////
            
            $start1 = microtime(true);
                
            $fileProcessedRows = roaminserter($fileTempPath);
            
                logPrinter(dateLog()."Row conut in file: ".count($fileProcessedRows->result)."\n");
                
                //print_r($fileProcessedRows);
                
                //remove temp file
                //unlink($fileTempPath);
                
                $rows = $fileProcessedRows->result;
                    print_r($rows);
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
                        $statementsToUpdateOnConflict .= "$itemFieldIndex = cbs_cbp_roam_data_payg_log.$itemFieldIndex+excluded.$itemFieldIndex,";
                    }    
                }

                // Remove last comma char. 
                $columnNamesCSV = rtrim($columnNamesCSV, ",");
                $columnNamesUniqKeyCSV = rtrim($columnNamesUniqKeyCSV, ",");
                $statementsToUpdateOnConflict = rtrim($statementsToUpdateOnConflict, ",");

                // Make final sql command.
                $sql = "INSERT INTO cbs_cbp_roam_data_payg_log ($columnNamesCSV) VALUES $insertCSV 
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
            

            ///////////////////////////////// cbs_cbp_data_cell_inserter //////////////////////////////
                $start1 = microtime(true);
                
                $fileProcessedRows = cbs_cbp_data_cell_inserter($fileTempPath);
                logPrinter(dateLog()."Row conut in file: ".count($fileProcessedRows->result)."\n");

                //remove temp file
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
                        $statementsToUpdateOnConflict .= "$itemFieldIndex = cbs_cbp_data_cell_log.$itemFieldIndex+excluded.$itemFieldIndex,";
                    }    
                }

                // Remove last comma char. 
                $columnNamesCSV = rtrim($columnNamesCSV, ",");
                $columnNamesUniqKeyCSV = rtrim($columnNamesUniqKeyCSV, ",");
                $statementsToUpdateOnConflict = rtrim($statementsToUpdateOnConflict, ",");

                // Make final sql command.
                $sql = "INSERT INTO cbs_cbp_data_cell_log ($columnNamesCSV) VALUES $insertCSV 
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
        }
//    }
sleep(2);
}