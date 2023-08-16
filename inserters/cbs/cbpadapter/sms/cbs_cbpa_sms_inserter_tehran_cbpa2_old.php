<?php

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/type_convertor.php";
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/database.php";

function getFilelist($path){
    $_res = [];
    $files = scandir($path);
    foreach($files as $file){
        if($file == "." || $file == '..'){continue;}
        $fullPath = $path."/".$file;
        if(is_file($fullPath) && explode(".", $fullPath)[count(explode(".", $fullPath))-1] == "plog"){
            if(count(explode("_", $fullPath))== 4){
            $_res[] = $fullPath;
            }
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
function capsInserter($file){
     $cbpaId = 455;
    $event_type = 1;

    $uniqCDRs = shell_exec("cat $file | grep \"The caps statistics of all conn: Caps=\" | sed -e \"s/ /|/g\" | awk -F \"|\" '{print $1$2,substr($9,6)}' | sed -e \"s/;//g\"");
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

        if(count($uniqCDR_Parts) != 2){
            //echo "this line scaped for length of array error \n ";
            continue;
        }
        $dateTime = $uniqCDR_Parts[0];
        $CAPS = $uniqCDR_Parts[1];
        
      
        $cdr_date = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7];
        $cdr_time = $dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11];
        $cdr_date_time = $cdr_date." ".$cdr_time;

        $cerrc_log_id = str_pad($cbpaId, 3, '0', STR_PAD_LEFT).str_pad($event_type, 2, '0', STR_PAD_LEFT).str_pad(strtotime($cdr_date_time), 10, '0', STR_PAD_LEFT);
        $results[] = [$cerrc_log_id, $cdr_date_time, $cbpaId, $event_type, $CAPS];
    }
    return $results;
}

function tpsInserter($file){
     $cbpaId = 455;
    $event_type = 2;

    $uniqCDRs = shell_exec("cat $file | grep \"The cbp tps statistics:\" | sed -e \"s/ /|/g\" | awk -F \"|\" '{print $1substr($2,0,4),substr($8,5)}' | sed -e \"s/;//g\" | awk '{seen[$1] += $2} END {for (i in seen) print i , seen[i]}'");
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

        if(count($uniqCDR_Parts) != 2){
            //echo "this line scaped for length of array error \n ";
            continue;
        }
        $dateTime = $uniqCDR_Parts[0];
        $TPS = $uniqCDR_Parts[1];
        
      
        $cdr_date = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7];
        $cdr_time = $dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11];
        $cdr_date_time = $cdr_date." ".$cdr_time;

        $cerrc_log_id = str_pad($cbpaId, 3, '0', STR_PAD_LEFT).str_pad($event_type, 2, '0', STR_PAD_LEFT).str_pad(strtotime($cdr_date_time), 10, '0', STR_PAD_LEFT);
        $results[] = [$cerrc_log_id, $cdr_date_time, $cbpaId, $event_type, $TPS];
    }
    return $results;
}
function delayInserter($file){
     $cbpaId = 455;
    $event_type = 3;

    $uniqCDRs = shell_exec("cat $file | grep \"The cbp average delaytime statistics:\" | sed -e \"s/ /|/g\" | awk -F \"|\" '{print $1substr($2,0,4),substr($9,10)}' | sed -e \"s/;//g\"  | awk '{seen[$1] += $2} END {for (i in seen) print i , seen[i]}'");
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

        if(count($uniqCDR_Parts) != 2){
            //echo "this line scaped for length of array error \n ";
            continue;
        }
        $dateTime = $uniqCDR_Parts[0];
        $DELAY = $uniqCDR_Parts[1];
        
      
        $cdr_date = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7];
        $cdr_time = $dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11];
        $cdr_date_time = $cdr_date." ".$cdr_time;

        $cerrc_log_id = str_pad($cbpaId, 3, '0', STR_PAD_LEFT).str_pad($event_type, 2, '0', STR_PAD_LEFT).str_pad(strtotime($cdr_date_time), 10, '0', STR_PAD_LEFT);
        $results[] = [$cerrc_log_id, $cdr_date_time, $cbpaId, $event_type, $DELAY];
    }
    return $results;
}

$city = '/cbshome/cdr_analysis/data/cbs/cbpadapter/sms/tehran/cbpa2';
while(true) {
    $fileList = getFilelist($city);
    foreach($fileList as $file){
        if (filesize($file) == 0 ){
            continue;
        }
        $logFile = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbpadapter/sms/cbpa2.log";
        $errorLog = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbpadapter/sms/error_cbpa2_sms.log";
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
        ////////////////////////////////////CAPS///////////////////////////////////
        $pureArrayList = capsInserter($file);
        $sql = "INSERT INTO  cbs_cbpa_sms_log (cerrc_log_id, cdr_date_time, cbp_id, event_type_id, e_count) VALUES ";
        foreach($pureArrayList as $pureArray){
            $sql.= "(";
            $sql.= TypeConvertorHelper::arrayToCSV($pureArray, true);
            $sql.= "),";
        }
        $sql = rtrim($sql, ",");
        $sql .= " ON DUPLICATE KEY UPDATE e_count = e_count";
        $start = microtime(true);
        query($sql);
        $finish_second = microtime(true)-$start;
        ////////////////////////////////////Delay///////////////////////////////////
        $pureArrayList = delayInserter($file);
        $sql3 = "INSERT INTO  cbs_cbpa_sms_log (cerrc_log_id, cdr_date_time, cbp_id, event_type_id, e_count) VALUES ";
        foreach($pureArrayList as $pureArray){
            $sql3.= "(";
            $sql3.= TypeConvertorHelper::arrayToCSV($pureArray, true);
            $sql3.= "),";
        }
        $sql3 = rtrim($sql3, ",");
        $sql3 .= " ON DUPLICATE KEY UPDATE e_count = e_count+VALUES(e_count)";
        $start = microtime(true);
        query($sql3);
        $finish_second = microtime(true)-$start;
    }
sleep(10);
}

