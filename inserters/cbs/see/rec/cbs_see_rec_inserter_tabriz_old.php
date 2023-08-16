<?php

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/type_convertor.php";
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/database.php";

// Cache talbles.
/**
 * msc_termination_reason_code
 * msc_termination_reason_id
 */
$cbs_see_rec_msc_termination_reason = query("SELECT * FROM cbs_see_rec_msc_termination_reason ");

/**
 * msc_address
 * msc_id
 */
$cbs_see_rec_msc_address = query("SELECT msc_id,msc_address  FROM cbs_see_rec_msc_address ");

/**
 * msc_call_type_code
 * msc_call_type_id
 */
$cbs_see_rec_msc_call_type = query("SELECT * FROM cbs_see_rec_msc_call_type");

/**
 * see_id
 * see_code
 */
$cbs_see_name = query("SELECT * FROM cbs_see_name");

// end cache.


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
    global $cbs_see_rec_msc_termination_reason, $cbs_see_name;
    $seeCode = shell_exec("stat $file | head -n 1 | cut -d \".\" -f 3");
    $seeCode = trim($seeCode);
    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_name, ['see_code', $seeCode]);
    $see_id = $cbs_see_name[$index]['see_id'];
    $regionId = $cbs_see_name[$index]['region_id'];

    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $29,$76}' | sed 's/.//14' |  sed 's/.//13' | sort -k1 -k2 -n | uniq -f1 -c");
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
function insertFailedCDR1($file){
    global $cbs_see_rec_msc_termination_reason, $cbs_see_rec_msc_address;
    /*$seeCode = shell_exec("stat $file | head -n 1 | cut -d \".\" -f 3");
    $seeCode = trim($seeCode);
    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_name, ['see_code', $seeCode]);
    $regionId = $cbs_see_name[$index]['region_id'];*/
    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $29,$76,$27}' | sed 's/.//14' |  sed 's/.//13' | sort -k1 -k2 -k3  -n | uniq -f1 -f2 -c");
    $uniqCDRs = explode("\n", $uniqCDRs);

    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 4){continue;}
        $count = $uniqCDR_Parts[0];
        $dateTime = $uniqCDR_Parts[1];
        $TERM_CODE = $uniqCDR_Parts[2];
        $MSC_ADD = $uniqCDR_Parts[3];
       // $CALL_TYPE = $uniqCDR_Parts[4];

        // MSC_Address
        $msc_id = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_address, ['msc_address', $MSC_ADD]);
        // Check for find match.
        if($index == -1){
            continue;
            //echo "OtherMSC: $MSC_ADD \n";
            $cbs_see_rec_msc_address = query("SELECT msc_id,msc_address  FROM cbs_see_rec_msc_address ");
            $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_address, ['msc_address', $MSC_ADD]);
            if($index == -1){
            $msc_id = newMscAddress($MSC_ADD);
            }else{
            $msc_id = $cbs_see_rec_msc_address[$index]['msc_id'];
            }
        }else{
            $msc_id = $cbs_see_rec_msc_address[$index]['msc_id'];
        }

        // Termination Reason
        $termination_reason_id = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_termination_reason, ['termination_reason_code', $TERM_CODE]);
        // Check for find match.
        if($index == -1){
            $cbs_see_rec_msc_termination_reason = query("SELECT * FROM cbs_see_rec_msc_termination_reason ");
            $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_termination_reason, ['termination_reason_code', $TERM_CODE]);
            if($index == -1){
            // Not any match find, so make it and use it. 
            $termination_reason_id = newTerminationReason($TERM_CODE);
            }else{
                $termination_reason_id = $cbs_see_rec_msc_termination_reason[$index]['termination_reason_id'];
            }
        }else{
            $termination_reason_id = $cbs_see_rec_msc_termination_reason[$index]['termination_reason_id'];
        }
        
        /*// Call_Type
        $call_type_id = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_call_type, ['call_type_code', $CALL_TYPE]);
        // Check for find match.
        if($index == -1){
            $cbs_see_rec_msc_call_type = query("SELECT * FROM cbs_see_rec_msc_call_type");
            $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_rec_msc_call_type, ['call_type_code', $CALL_TYPE]);
            if($index == -1){
            // Not any match find, so make it and use it. 
            $call_type_id = newCallType($CALL_TYPE);
            }else{
                $call_type_id = $cbs_see_rec_msc_call_type[$index]['call_type_id'];
            }
        }else{
            $call_type_id = $cbs_see_rec_msc_call_type[$index]['call_type_id'];
        }*/

        $cdr_date = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7];
        $cdr_time = $dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11];
        $cdr_date_time = $cdr_date." ".$cdr_time;
        
        // Calc log_id
        $serrc_log_id = str_pad(strtotime($cdr_date_time), 10, '0', STR_PAD_LEFT).str_pad($termination_reason_id, 3, '0', STR_PAD_LEFT).str_pad($msc_id, 3, '0', STR_PAD_LEFT);
        $results[] = [$serrc_log_id, $cdr_date_time, $msc_id, $termination_reason_id, $count];
    }
    // Clear content of file after calc.
    
    /*$f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }*/
    return $results;
}

//$cityList = getDirList('/cbshome/cdr_analysis/data/cbs/see/rec');
$city = '/cbshome/cdr_analysis/data/cbs/see/rec/tabriz';
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
                if (filesize($file) == 0 ){continue;}
                $logFile = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/see/rec/tabriz.log";
                $errorLog = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/see/rec/error_tabriz_rec.log";
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
                    /*$f = @fopen("$file", "r+");
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
                $sql = "INSERT INTO  cbs_see_rec_err_code_log (serrc_log_id, cdr_date_time, termination_reason_id, region_id, see_id, e_count) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql.= "(";
                    $sql.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql.= "),";
                }
                // Trim last comma.
                $sql = rtrim($sql, ",");
                // Add statement to update in duplicate primary key.
                $sql .= " ON DUPLICATE KEY UPDATE e_count = e_count+VALUES(e_count)";
                // It's ready to insert.
                $start = microtime(true);
                query($sql);
                $finish_second = microtime(true)-$start;
                //echo "Finished time: $finish_second Sec. \n";
                
                // insert MSC records
                //echo "Feed file1: $file \n";
                $pureArrayList1 = insertFailedCDR1($file);
                //echo "row conut in file1: ".count($pureArrayList1)."\n";
                $sql1 = "INSERT INTO  cbs_see_rec_msc_log (serrc_log_id, cdr_date_time, msc_id, termination_reason_id, e_count) VALUES ";
                foreach($pureArrayList1 as $pureArray1){
                    $sql1.= "(";
                    $sql1.= TypeConvertorHelper::arrayToCSV($pureArray1, true);
                    $sql1.= "),";
                }
                // Trim last comma.
                $sql1 = rtrim($sql1, ",");
                //echo "SQL1: $sql1 \n";
                // Add statement to update in duplicate primary key.
                $sql1 .= " ON DUPLICATE KEY UPDATE e_count = e_count+VALUES(e_count)";
                // It's ready to insert.
                $startSQL = microtime(true);
                query($sql1);
                $finish_second = microtime(true)-$startSQL;
                //echo "Finished time SQL1: $finish_second Sec. \n";
            }
        }
    //}
    sleep(1);
}

