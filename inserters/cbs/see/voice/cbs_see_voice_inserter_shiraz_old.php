<?php

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/type_convertor.php";
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/database.php";

// Cache talbles.
/**
 * subkey_class_id
 * subkey_code_class_code
 */
//$cbs_see_voice_subkey_class = query("SELECT subkey_class_id, CONCAT(subkey_code,',', class_code) AS `subkey_code_class_code` FROM cbs_see_voice_subkey_class ");

/**
 * see_id
 * see_code
 */
$cbs_see_name = query("SELECT * FROM cbs_see_name");

/**
 * serrc_id
 * serrc_code
 */
$cbs_see_voice_err_code = query("SELECT serrc_id, serrc_code FROM cbs_see_voice_err_code");


// end cache.


/*function newSubkeyClass($subkey_code, $class_code){
    $subkey_class_desc = 'new-item';
    $query = "INSERT INTO cbs_see_voice_subkey_class (subkey_code, class_code, subkey_class_desc) VALUES (?, ?, ?)";
    return query($query, [$subkey_code, $class_code, $subkey_class_desc]);
}*/

function newErrCode($serrc_code){
    $serrc_desc = "new-item";
    $query = "INSERT INTO cbs_see_voice_err_code (serrc_code, serrc_desc) VALUES (?, ?)";
    return query($query, [$serrc_code, $serrc_desc]);
}


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
 * get file path and get sql command for it.
 * 
 * @param string $file path of file to feed.
 * @return array an insertable array to database, [see_id, subkey_class_id, serrc_id, count, cdr_date, cdr_time]
 */
function insertFailedCDR($file){
    global $cbs_see_voice_err_code, $cbs_see_name;
    $seeCode = shell_exec("stat $file | head -n 1 | cut -d \".\" -f 3");
    $seeCode = trim($seeCode);
    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_name, ['see_code', $seeCode]);
    $see_id = $cbs_see_name[$index]['see_id'];
    $regionId = $cbs_see_name[$index]['region_id'];

    //$cdr_date = shell_exec("stat $file | head -n 6 | tail -n 1 | cut -d \" \" -f 2");
    //$cdr_date = trim($cdr_date);
    //$cdr_time = shell_exec("stat $file | head -n 6 | tail -n 1 | cut -d \" \" -f 3 | cut -d \".\" -f 1");
    //$cdr_time = trim($cdr_time);
    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $4,$8,$2}' | sort -k1 -k2 -k3 -n | uniq -f1 -f2 -c");
    $uniqCDRs = explode("\n", $uniqCDRs);

    //echo "shell_exec awk for File $file: \n";
    //echo "uniqCDRs results: \n";

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
        $count = $uniqCDR_Parts[0];
        $dateTime = $uniqCDR_Parts[1];
        $ERROR_CODE = $uniqCDR_Parts[2];
        $SUB_KEY = $uniqCDR_Parts[3];
       // $CLASS_CODE = $uniqCDR_Parts[4];
        // 1,2
        $subkey_code = $SUB_KEY; 
        /*
        $subkey_class_id = '0';
        $SUB_KEY_CODE_CLASS_CODE = $SUB_KEY.",".$CLASS_CODE;
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_voice_subkey_class, ['subkey_code_class_code', $SUB_KEY_CODE_CLASS_CODE]);
        // Check for find match.
        if($index == -1){
            // Not any match find, so make it and use it. 
            $subkey_class_id = newSubkeyClass($SUB_KEY, $CLASS_CODE);
        }else{
            $subkey_class_id = $cbs_see_voice_subkey_class[$index]['subkey_class_id'];
        }
        */
        // 7
        $serrc_id = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_voice_err_code, ['serrc_code', $ERROR_CODE]);
        // Check for find match.
        if($index == -1){
            // Not any match find, so make it and use it. 
            $serrc_id = newErrCode($ERROR_CODE);

            //echo "Make $ERROR_CODE in new error \n";
        }else{
            $serrc_id = $cbs_see_voice_err_code[$index]['serrc_id'];
        }
        $cdr_date = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7];
        $cdr_time = $dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11];
        $cdr_date_time = $cdr_date." ".$cdr_time;

        //echo "Expected date time : $cdr_date_time \n";
        //$results[] = [$see_id, $subkey_class_id, $serrc_id, $count, $cdr_date, $cdr_time];
        // Calc log_id
        $serrc_log_id = str_pad($see_id, 2, '0', STR_PAD_LEFT).str_pad($regionId, 2, '0', STR_PAD_LEFT).str_pad($serrc_id, 2, '0', STR_PAD_LEFT).str_pad($subkey_code, 2, '0', STR_PAD_LEFT).str_pad(strtotime($cdr_date_time), 10, '0', STR_PAD_LEFT);
        //echo "Expected primary key: $serrc_log_id \n";

        $results[] = [$serrc_log_id, $cdr_date_time, $ERROR_CODE, $regionId, $see_id, $subkey_code, $count];
        
        //echo "\n\n\n";
        
    }

    // Copy file in history.

    //$destinationForHistory = "/cbshome/cdr_analysis/data/cbs/see/history";
    //shell_exec("cp $file $destinationForHistory");

    // Clear content of file after calc.
    /*$f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }*/
    return $results;
}

//$cityList = getDirList('/cbshome/cdr_analysis/data/cbs/see/voice');
$city = '/cbshome/cdr_analysis/data/cbs/see/voice/shiraz';
while(true) {
    //foreach($cityList as $city){
       $dateDirList = getDirList($city);

        //echo "City: $city \n";
        //print_r($dateDirList);
        //echo "\n\n";

        foreach($dateDirList as $dateDir){
            
            $fileList = getFilelist($dateDir);

            //echo "Date Dir: $dateDir \n";
            //print_r($fileList);
            //echo "\n\n";

            foreach($fileList as $file){
                if (filesize($file) == 0 ){
                    continue;
                }
                $logFile = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/see/voice/shiraz.log";
                $errorLog = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/see/voice/error_shiraz_voice.log";
                if (!is_dir($logFile)) {
                    echo "$logFile dir not exist \n";
                    shell_exec("mkdir $logFile");
                };
                $fileName = basename($file);
                //echo "$fileName \n\n";
                if(file_exists($logFile."/".$fileName)){
                    $errorText = "duplicate file: $file \n";
                    $l = fopen("$errorLog", "a");
                        //fwrite($l, $errorText);
                        fclose($l);
                   /* $f = @fopen("$file", "r+");
                    if ($f !== false) {
                        ftruncate($f, 0);
                        fclose($f);
                    }*/
                continue;
                };
                shell_exec("cd $logFile;touch $fileName");

                // Get pure file.
                //echo "Feed file: $file \n";
                $pureArrayList = insertFailedCDR($file);
                //echo "row conut in file: ".count($pureArrayList)."\n";
                $sql = "INSERT INTO  cbs_see_voice_err_code_log (serrc_log_id, cdr_date_time, serrc_code, region_id, see_id, subkey_code, e_count) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql.= "(";
                    $sql.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql.= "),";
                }
                // Trim last comma.
                $sql = rtrim($sql, ",");
                // Add statement to update in duplicate primary key.
                $sql .= " ON DUPLICATE KEY UPDATE e_count = e_count+VALUES(e_count)";

                //echo "Query: $sql \n";

                // It's ready to insert.
                $start = microtime(true);
                query($sql);
                $finish_second = microtime(true)-$start;
                //echo "Finished time: $finish_second Sec. \n";

                //echo "\n\n\n\n\n\n\n\n\n\n\n\n";
            }
        }
    //}
    sleep(1);
}

