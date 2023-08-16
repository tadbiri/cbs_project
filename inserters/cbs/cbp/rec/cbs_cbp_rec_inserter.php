<?php

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/type_convertor.php";
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/database.php";

// Cache talbles.


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


$cbs_cbp_cellid = query("SELECT * FROM cbs_cbp_cellid");
// end cache.


function newcellid ($cell_code,$area_code){
    echo "$cell_code , $area_code";
    echo "\n \n \n \n";
    $query = "INSERT INTO cbs_cbp_cellid (cell_code, area_code) VALUES (?, ?)";
    return query($query, [$cell_code,$area_code]);
}

/**
 * get file path and get sql command for it.
 * 
 * @param string $file path of file to feed.
 * @return array an insertable array to database, [cbp_id, cerrc_id, count, cdr_date, cdr_time]
 */

//cell id 

 function cell_id($file){
     global $cbs_cbp_cellid;

    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{if ($527 == \"98\") print $501,$525}'");   
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        
        if(count($uniqCDR_Parts) != 2){continue;}
        $cell_code = $uniqCDR_Parts[0];
        $area_code = $uniqCDR_Parts[1];

        //print_r ($cell_code);
        //echo "\n \n \n \n";
        //print_r ($area_code);

        
        $cell_id = '0';
        $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_cellid, ['cell_code', $cell_code]);
        $index1 = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_cellid, ['area_code', $area_code]);
        
        // Check for find match.
        if($index == -1 || $index1 == -1){
            $cbs_cbp_cellid = query("SELECT * FROM cbs_cbp_cellid");
            $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_cellid, ['cell_code', $cell_code]);
            $index1 = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_cbp_cellid, ['area_code', $area_code]);
                if($index == -1 || $index1 == -1){
                    // Not any match find, so make it and use it. 
                    newcellid($cell_code,$area_code);
                }
                else{ continue;}
            }
            else{ continue;}

        //$results[] = [$cell_id,$area_code];
        //echo "\n";
        //print_r ($results);
    }
    $f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    //return $results;
}




function idd_attemps_iraq($file){

    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{if ($521 == \"98\" && $524 == \"98\" && $527 == \"98\" && $530 == \"964\") print substr($13,1,12)}' | uniq -c");   
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 2){continue;}
        $dateTime = $uniqCDR_Parts[1];
        $idd_attemps = $uniqCDR_Parts[0];

        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*4.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        $results[] = [$cdr_date_time,$idd_attemps];
        //echo "\n";
        //print_r ($results);
    }
    return $results;
}
function idd_iraq($file){

    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{if ($521 == \"98\" && $524 == \"98\" && $527 == \"98\" && $530 == \"964\") print $13,$35,$42+$49,$560}'");  
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 4){continue;}
        $dateTime = $uniqCDR_Parts[0];
        $idd_min = $uniqCDR_Parts[1];
        $idd_debit_amount = substr($uniqCDR_Parts[2],0,-3);
        $idd_free_amount = substr($uniqCDR_Parts[3],0,-3);

        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*4.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        $results[] = [$cdr_date_time,$idd_min,$idd_debit_amount,$idd_free_amount];
        //print_r ($results);
        //echo "\n";
    }
    return $results;
}

function roam_attemps_iraq($file){

    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{if ($521 == \"98\" && $524 == \"964\" && $498 == \"1\") print substr($13,1,12)}' | uniq -c");   
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 2){continue;}
        $dateTime = $uniqCDR_Parts[1];
        $roam_attemps = $uniqCDR_Parts[0];

        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*4.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        $results[] = [$cdr_date_time,$roam_attemps];
        //print_r ($results);
        //echo "\n";
    }
    return $results;
}
function roam_iraq($file){

    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{if ($521 == \"98\" && $524 == \"964\" && $498 == \"1\") print $13,$35,$42+$49,$560}'");   
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 4){continue;}
        $dateTime = $uniqCDR_Parts[0];
        $roam_min = $uniqCDR_Parts[1];
        $roam_debit_amount = substr($uniqCDR_Parts[2],0,-3);
        $roam_free_amount = substr($uniqCDR_Parts[3],0,-3);

        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*4.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        $results[] = [$cdr_date_time,$roam_min,$roam_debit_amount,$roam_free_amount];
        //print_r ($results);
        //echo "\n";
    }
    // Clear content of file after calc.
    /*$f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }*/
    return $results;
}

function mt_roam_attemps_iraq($file){

    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{if ($527 == \"98\" && $530 == \"964\" && $498 == \"2\") print substr($13,1,12)}' | uniq -c");   
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 2){continue;}
        $dateTime = $uniqCDR_Parts[1];
        $mt_roam_attemps = $uniqCDR_Parts[0];

        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*4.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        $results[] = [$cdr_date_time,$mt_roam_attemps];
        //print_r ($results);
        //echo "\n";
    }
    return $results;
}
function mt_roam_iraq($file){

    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{if ($527 == \"98\" && $530 == \"964\" && $498 == \"2\") print $13,$35,$396}'");   
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        if(count($uniqCDR_Parts) != 3){continue;}
        $dateTime = $uniqCDR_Parts[0];
        $mt_roam_min = $uniqCDR_Parts[1];
        $mt_roam_debit_amount = substr($uniqCDR_Parts[2],0,-3);

        $cdr_date_time = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7]." ".$dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11].":"."0"."0";
        $cdr_date_time = strtotime("$cdr_date_time");
        $cdr_date_time = $cdr_date_time+(60*60*4.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        $results[] = [$cdr_date_time,$mt_roam_min,$mt_roam_debit_amount];
        //print_r ($results);
        //echo "\n";
    }
    // Clear content of file after calc.
    $f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }
    return $results;
}

$cityList = getDirList('/cbshome/cdr_analysis/data/cbs/cbp/rec');
//$city = '/cbshome/cdr_analysis/data/cbs/cbp/rec/mashhad';
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
                /*$logFile = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/rec/file.log";
                $errorLog = "/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/rec/error_rec.log";
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
                */
                // Get pure file.
                //echo "Feed file: $file \n";
                /*
                $pureArrayList = idd_attemps_iraq($file);
                //echo "idd_attemps_iraq row conut in file: ".count($pureArrayList)."\n";
                $sql = "INSERT INTO  cbs_cbp_rec_iraq_log (cdr_date_time, idd_attemps) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql.= "(";
                    $sql.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql.= "),";
                }
                // Trim last comma.
                $sql = rtrim($sql, ",");
                // Add statement to update in duplicate primary key.
                $sql .= " ON DUPLICATE KEY UPDATE idd_attemps = idd_attemps+VALUES(idd_attemps)";
                // It's ready to insert.
                $start = microtime(true);
                query($sql);
                $finish_second = microtime(true)-$start; 
                //echo "idd_attemps_iraq Finished time: $finish_second Sec. \n"; 

                
                $pureArrayList = idd_iraq($file);
                //echo "idd_iraq row conut in file: ".count($pureArrayList)."\n";
                $sql1 = "INSERT INTO  cbs_cbp_rec_iraq_log (cdr_date_time, idd_min, idd_debit_amount, idd_free_amount) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql1.= "(";
                    $sql1.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql1.= "),";
                }
                // Trim last comma.
                $sql1 = rtrim($sql1, ",");
                // Add statement to update in duplicate primary key.
                $sql1 .= " ON DUPLICATE KEY UPDATE idd_min = idd_min+VALUES(idd_min), idd_debit_amount = idd_debit_amount+VALUES(idd_debit_amount), idd_free_amount = idd_free_amount+VALUES(idd_free_amount)";
                // It's ready to insert.
                $start = microtime(true);
                query($sql1);
                $finish_second = microtime(true)-$start;
                //echo "idd_iraq Finished time: $finish_second Sec. \n\n\n"; 
                //print_r($sql1);
                //echo "\n\n\n";

                $pureArrayList = roam_attemps_iraq($file);
                //echo "row conut in file: ".count($pureArrayList)."\n";
                $sql2 = "INSERT INTO  cbs_cbp_rec_iraq_log (cdr_date_time, roam_attemps) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql2.= "(";
                    $sql2.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql2.= "),";
                }
                // Trim last comma.
                $sql2 = rtrim($sql2, ",");
                // Add statement to update in duplicate primary key.
                $sql2 .= " ON DUPLICATE KEY UPDATE roam_attemps = roam_attemps+VALUES(roam_attemps)";
                // It's ready to insert.
                //$start = microtime(true);
                query($sql2);
                //$finish_second = microtime(true)-$start;


                
                $pureArrayList = roam_iraq($file);
                //echo "row conut in file: ".count($pureArrayList)."\n";
                $sql3 = "INSERT INTO  cbs_cbp_rec_iraq_log (cdr_date_time, roam_min, roam_debit_amount, roam_free_amount) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql3.= "(";
                    $sql3.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql3.= "),";
                }
                // Trim last comma.
                $sql3 = rtrim($sql3, ",");
                // Add statement to update in duplicate primary key.
                $sql3 .= " ON DUPLICATE KEY UPDATE roam_min = roam_min+VALUES(roam_min), roam_debit_amount = roam_debit_amount+VALUES(roam_debit_amount), roam_free_amount = roam_free_amount+VALUES(roam_free_amount)";
                // It's ready to insert.
                $start = microtime(true);
                query($sql3);
                $finish_second = microtime(true)-$start;
                //echo "Finished time: $finish_second Sec. \n";


                $pureArrayList = mt_roam_attemps_iraq($file);
                //echo "row conut in file: ".count($pureArrayList)."\n";
                $sql4 = "INSERT INTO  cbs_cbp_rec_iraq_log (cdr_date_time, mt_roam_attemps) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql4.= "(";
                    $sql4.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql4.= "),";
                }
                // Trim last comma.
                $sql4 = rtrim($sql4, ",");
                // Add statement to update in duplicate primary key.
                $sql4 .= " ON DUPLICATE KEY UPDATE mt_roam_attemps = mt_roam_attemps+VALUES(mt_roam_attemps)";
                // It's ready to insert.
                //$start = microtime(true);
                query($sql4);
                //$finish_second = microtime(true)-$start;


                
                $pureArrayList = mt_roam_iraq($file);
                //echo "row conut in file: ".count($pureArrayList)."\n";
                $sql5 = "INSERT INTO  cbs_cbp_rec_iraq_log (cdr_date_time, mt_roam_min, mt_roam_debit_amount) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql5.= "(";
                    $sql5.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql5.= "),";
                }
                // Trim last comma.
                $sql5 = rtrim($sql5, ",");
                // Add statement to update in duplicate primary key.
                $sql5 .= " ON DUPLICATE KEY UPDATE mt_roam_min = mt_roam_min+VALUES(mt_roam_min), mt_roam_debit_amount = mt_roam_debit_amount+VALUES(mt_roam_debit_amount)";
                // It's ready to insert.
                $start = microtime(true);
                query($sql5);
                $finish_second = microtime(true)-$start;
                //echo "Finished time: $finish_second Sec. \n";
                */
                
                $pureArrayList = cell_id($file);
                /*
                //echo "row conut in file: ".count($pureArrayList)."\n";
                $sql6 = "INSERT INTO  cbs_cbp_rec_iraq_log (cdr_date_time, mt_roam_min, mt_roam_debit_amount) VALUES ";
                foreach($pureArrayList as $pureArray){
                    $sql6.= "(";
                    $sql6.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql6.= "),";
                }
                // Trim last comma.
                $sql6 = rtrim($sql6, ",");
                // Add statement to update in duplicate primary key.
                $sql6 .= " ON DUPLICATE KEY UPDATE mt_roam_min = mt_roam_min+VALUES(mt_roam_min), mt_roam_debit_amount = mt_roam_debit_amount+VALUES(mt_roam_debit_amount)";
                // It's ready to insert.
                $start = microtime(true);
                query($sql6);
                $finish_second = microtime(true)-$start;
                //echo "Finished time: $finish_second Sec. \n";
                */
            }
        }
    }
    sleep(1);
}

 