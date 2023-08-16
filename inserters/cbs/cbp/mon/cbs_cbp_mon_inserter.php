<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";


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

function pkg_iraq($file){

    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{if($503== 704753 || $503 == 704750 || $503 == 704752 || $503 == 704751) print $13,$503}' | sort -k1 -k2 -n | uniq -c");   
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 3){continue;}
        $dateTime = $uniqCDR_Parts[1];
        $offering_code = $uniqCDR_Parts[2];
        $e_count = $uniqCDR_Parts[0];



        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*4.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        $results[] = [$cdr_date_time,$offering_code,$e_count];
        //print_r ($results);
    }

    // Clear content of file after calc.
    $f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }
    return $results;
}

$cityList = getDirList('/cbshome/cdr_analysis/data/cbs/cbp/mon');
//$city = '/cbshome/cdr_analysis/data/cbs/cbp/mon';
while(true) {
    foreach($cityList as $city){
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
                $logFile = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/mon/mon.log";
                $errorLog = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/mon/error_mon_data.log";
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
                    $f = @fopen("$file", "r+");
                    if ($f !== false) {
                        ftruncate($f, 0);
                        fclose($f);
                    }
                continue;
                };
                shell_exec("cd $logFile;touch $fileName");
                // Get pure file.
                //echo "Feed file: $file \n";
                
                $pureArrayList = pkg_iraq($file);
                //echo "row conut in file: ".count($pureArrayList)."\n";
                $sql1 = "INSERT INTO  cbs_cbp_mon_pkg (cdr_date_time, offering_code, e_count) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql1.= "(";
                    $sql1.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql1.= "),";
                }
                // Trim last comma.
                $sql1 = rtrim($sql1, ",");
                // Add statement to update in duplicate primary key.
                $sql1 .= " ON DUPLICATE KEY UPDATE e_count = e_count+VALUES(e_count) ";
                // It's ready to insert.
                //$start = microtime(true);
                query($sql1);
                
            }
        }
    }
    sleep(1);
}

 