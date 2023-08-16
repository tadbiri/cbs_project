<?php

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/type_convertor.php";
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/database.php";

// Cache talbles.

/**
 * cbp_id
 * cbp_code
 */
$cbs_cbp_name = query("SELECT * FROM cbs_cbp_name");

/**
 * cerrc_id
 * cerrc_code
 */
$cbs_cbp_data_err_code = query("SELECT cerrc_id, cerrc_code FROM cbs_cbp_data_err_code");

/**
 * rg_id
 * rg_code
 */
$cbs_cbp_data_rg_code = query("SELECT rg_id, rg_code FROM cbs_cbp_data_rg_code");
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
 * @return array an insertable array to database, [cbp_id, cerrc_id, count, cdr_date, cdr_time]
 */

function subinseter($file){
    $regionId = '3';

    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $13,$26,$57}'");
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 3){continue;}
        $dateTime = $uniqCDR_Parts[0];
        $pri_identity = $uniqCDR_Parts[1];
        $acct_type = $uniqCDR_Parts[2];
        
        if ($acct_type == 0){
            $sub_type = 'pps';
        } else {
            $sub_type = 'pos';
        }
        

        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*4.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        $results[] = [$cdr_date_time,$regionId,$sub_type,$pri_identity];
        //print_r ($results);
        //echo "\n";
    }
    return $results;
}

function data_roam_iraq($file){

    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{if ($523 == \"964\") print $13,$502,$42+$49,$52,$542,$535}'");   
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 6){continue;}
        $dateTime = $uniqCDR_Parts[0];
        $actuall_usage = $uniqCDR_Parts[1];
        $debit_amount = substr($uniqCDR_Parts[2],0,-3);
        $free_amount = substr($uniqCDR_Parts[4],0,-3);
        $rating_group = $uniqCDR_Parts[5];
        $free_usage = $uniqCDR_Parts[3];
        
        if($rating_group == 901 && $free_usage == 0 ){
            $free_usage = $actuall_usage;
        }elseif($rating_group == 90){
            $free_usage = $actuall_usage;
        }elseif($rating_group == 80){
            $free_usage = $actuall_usage;
        }else{
            $free_usage = $uniqCDR_Parts[3];
        }
        
        

        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*4.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        $results[] = [$cdr_date_time,$actuall_usage,$debit_amount,$free_usage,$free_amount];
        //print_r ($results);
        //echo "\n";
    }
    return $results;
}


function insertFailedCDR($file){
    global $cbs_cbp_data_rg_code, $cbs_cbp_data_err_code, $cbs_cbp_name;
    $cbpCode = shell_exec("stat $file | head -n1 | cut -d \"_\" -f 3");
    $cbpCode = trim($cbpCode);
    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_name, ['cbp_code', $cbpCode]);
    $cbp_id = $cbs_cbp_name[$index]['cbp_id'];
    $regionId = $cbs_cbp_name[$index]['region_id'];

    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $13,$29,$535}' | sort -k1 -k2 -k3 -n | uniq -f1 -f2 -c");
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 4){continue;}
        $count = $uniqCDR_Parts[0];
        $dateTime = $uniqCDR_Parts[1];
        $ERROR_CODE = $uniqCDR_Parts[2];
        $RATING_GROUP = $uniqCDR_Parts[3];
        // Error code id
        $cerrc_id = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_data_err_code, ['cerrc_code', $ERROR_CODE]);
        // Check for find match.
        if($index == -1){
            $cbs_cbp_data_err_code = query("SELECT cerrc_id, cerrc_code FROM cbs_cbp_data_err_code");
            $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_data_err_code, ['cerrc_code', $ERROR_CODE]);
            if($index == -1){
            // Not any match find, so make it and use it. 
            $cerrc_id = newErrCode($ERROR_CODE);
            }else{
                $cerrc_id = $cbs_cbp_data_err_code[$index]['cerrc_id'];
            }
        }else{
            $cerrc_id = $cbs_cbp_data_err_code[$index]['cerrc_id'];
        }
        // Rating group id
        $rg_id = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_data_rg_code, ['rg_code', $RATING_GROUP]);
        // Check for find match.
        if($index == -1){
            $cbs_cbp_data_rg_code = query("SELECT rg_id, rg_code FROM cbs_cbp_data_rg_code");
            $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_data_rg_code, ['rg_code', $RATING_GROUP]);
            if($index == -1){
            // Not any match find, so make it and use it. 
            $rg_id = newRGCode($RATING_GROUP);
            }else{
                $rg_id = $cbs_cbp_data_rg_code[$index]['rg_id'];
            }
        }else{
            $rg_id = $cbs_cbp_data_rg_code[$index]['rg_id'];
        }

        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*4.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        // Calc log_id
        $cerrc_log_id = str_pad($cbp_id, 3, '0', STR_PAD_LEFT).str_pad($regionId, 1, '0', STR_PAD_LEFT).str_pad($cerrc_id, 2, '0', STR_PAD_LEFT).str_pad($rg_id, 2, '0', STR_PAD_LEFT).str_pad(strtotime($cdr_date_time), 10, '0', STR_PAD_LEFT);
        $results[] = [$cerrc_log_id, $cdr_date_time, $cerrc_id, $regionId, $cbp_id, $rg_id, $count];
        
        
    }
    // Clear content of file after calc.
    /*$f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }*/
    return $results;
}

//$cityList = getDirList('/cbshome/cdr_analysis/data/cbs/cbp/data/shiraz');
$city = '/cbshome/cdr_analysis/data/cbs/cbp/data/shiraz';
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
                $logFile = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/data/shiraz.log";
                $errorLog = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/data/error_shiraz_data.log";
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

                /*$pureArrayList = subinseter($file);
                //echo "row conut in file: ".count($pureArrayList)."\n";
                $sql1 = "INSERT INTO  cbs_cbp_data_total_sub_log (sub_datetime, region_id, sub_type, pri_identity) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql1.= "(";
                    $sql1.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql1.= "),";
                }
                // Trim last comma.
                $sql1 = rtrim($sql1, ",");
                // Add statement to update in duplicate primary key.
                //$sql .= " ON DUPLICATE KEY UPDATE e_count = e_count+VALUES(e_count)";
                // It's ready to insert.
                $start = microtime(true);
                query($sql1);
                $finish_second = microtime(true)-$start;
                */



                $pureArrayList = data_roam_iraq($file);
                //echo "row conut in file: ".count($pureArrayList)."\n";
                $sql1 = "INSERT INTO  cbs_cbp_data_iraq_log (cdr_date_time, actuall_usage, debit_amount, free_usage, free_amount) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql1.= "(";
                    $sql1.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql1.= "),";
                }
                // Trim last comma.
                $sql1 = rtrim($sql1, ",");
                // Add statement to update in duplicate primary key.
                $sql1 .= " ON DUPLICATE KEY UPDATE actuall_usage = actuall_usage+VALUES(actuall_usage), debit_amount = debit_amount+VALUES(debit_amount), free_usage = free_usage+VALUES(free_usage), free_amount = free_amount+VALUES(free_amount) ";
                // It's ready to insert.
                //$start = microtime(true);
                query($sql1);
                //$finish_second = microtime(true)-$start;




                $pureArrayList = insertFailedCDR($file);
                //echo "row conut in file: ".count($pureArrayList)."\n";
                $sql = "INSERT INTO  cbs_cbp_data_err_code_log (cerrc_log_id, cdr_date_time, cerrc_id, region_id, cbp_id, rg_id, e_count) VALUES ";
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
            }
        }
    //}
    sleep(1);
}

