<?php

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/type_convertor.php";
require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/database.php";


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
        if(is_file($fullPath) && explode(".", $fullPath)[count(explode(".", $fullPath))-1] == "csv"){
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
function insertUSN($file){
    $uniqCDRs = shell_exec("cat $file | grep -i \"s1ap link down\" | grep -i \"EnodeBCode\" | awk -F \",\" '{print $3,$4,$5,substr($14,13),$30,$31}' | sed 's/ DST//g'");
    $uniqCDRs = explode("\n", $uniqCDRs);
    // an array to hold results.
    $results = [];
    foreach($uniqCDRs as $uniqCDR){
        $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
        $uniqCDR_Parts = explode(" ", $uniqCDR);
        //print_r($uniqCDR_Parts);
        //echo "\n\n";
        if(count($uniqCDR_Parts) < 8){
            //echo "insertUSN under 8 \n\n";
            //print_r($uniqCDR_Parts);
            //echo "\n\n";
            continue;
        }
        if(count($uniqCDR_Parts) == 9){
            $alarm_name = $uniqCDR_Parts[0]." ".$uniqCDR_Parts[1]." ".$uniqCDR_Parts[2];
            $ne_type = $uniqCDR_Parts[3];
            $alarm_src = $uniqCDR_Parts[4];
            $enodeb_code = $uniqCDR_Parts[5];
            $start_time = $uniqCDR_Parts[6]." ".$uniqCDR_Parts[7];
            $end_time = '0000-00-00 00:00:00';
            //echo "wnd time: $end_time\n";
            $duration = "-1";
            $results[] = [$start_time, $end_time, $alarm_name, $ne_type, $alarm_src,  $enodeb_code, $duration];
            //print_r($results);
            //echo "\n";
        }
        if(count($uniqCDR_Parts) == 10){
            $alarm_name = $uniqCDR_Parts[0]." ".$uniqCDR_Parts[1]." ".$uniqCDR_Parts[2];
            $ne_type = $uniqCDR_Parts[3];
            $alarm_src = $uniqCDR_Parts[4];
            $enodeb_code = $uniqCDR_Parts[5];
            $start_time = $uniqCDR_Parts[6]." ".$uniqCDR_Parts[7];
            $end_time = $uniqCDR_Parts[8]." ".$uniqCDR_Parts[9];
            $start_time1 = strtotime("$start_time");
            $end_time1 = strtotime("$end_time"); 
            $duration = $end_time1 - $start_time1;

            $results[] = [$start_time, $end_time, $alarm_name, $ne_type, $alarm_src,  $enodeb_code, $duration];
        }
        
        //print_r($results);
        //echo "\n";
        }
        //print_r($results);
        //echo "\n";
    return $results;
    }

    function insertVUSN($file){
        $uniqCDRs = shell_exec("cat $file | grep -i \"s1ap link down\" | grep -i \"enodeb id\" | awk -F \",\" '{print $3,$4,$5,substr($10,12),$11,$12}' | sed 's/\"//g' | sed 's/ DST//g' ");
        $uniqCDRs = explode("\n", $uniqCDRs);
        // an array to hold results.
        $results = [];
        foreach($uniqCDRs as $uniqCDR){
            $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
            $uniqCDR_Parts = explode(" ", $uniqCDR);
            if(count($uniqCDR_Parts) < 8){
                //echo "insertUSN under 8 \n\n";
                //print_r($uniqCDR_Parts);
                //echo "\n\n";
                continue;
            }
            if(count($uniqCDR_Parts) == 9){
                $alarm_name = $uniqCDR_Parts[0]." ".$uniqCDR_Parts[1]." ".$uniqCDR_Parts[2];
                $ne_type = $uniqCDR_Parts[3];
                $alarm_src = $uniqCDR_Parts[4];
                $enodeb_code = $uniqCDR_Parts[5];
                $start_time = $uniqCDR_Parts[6]." ".$uniqCDR_Parts[7];
                $end_time = '0000-00-00 00:00:00';
                //echo "wnd time: $end_time\n";
                $duration = "-1";
                $results[] = [$start_time, $end_time, $alarm_name, $ne_type, $alarm_src,  $enodeb_code, $duration];
                //print_r($results);
                //echo "\n";
            }
            if(count($uniqCDR_Parts) == 10){
                $alarm_name = $uniqCDR_Parts[0]." ".$uniqCDR_Parts[1]." ".$uniqCDR_Parts[2];
                $ne_type = $uniqCDR_Parts[3];
                $alarm_src = $uniqCDR_Parts[4];
                $enodeb_code = $uniqCDR_Parts[5];
                $start_time = $uniqCDR_Parts[6]." ".$uniqCDR_Parts[7];
                $end_time = $uniqCDR_Parts[8]." ".$uniqCDR_Parts[9];
                $start_time1 = strtotime("$start_time");
                $end_time1 = strtotime("$end_time"); 
                $duration = $end_time1 - $start_time1;
    
                $results[] = [$start_time, $end_time, $alarm_name, $ne_type, $alarm_src,  $enodeb_code, $duration];
            }
            
        }
        //print_r($results);
        //echo "\n";
        return $results;
    }
 

$dir = '/cbshome/cdr_analysis';

$fileList = getFilelist($dir);

            foreach($fileList as $file){
                
                // Get pure file.
                echo "Feed file: $file \n";
                $pureArrayList1 = insertUSN($file);
                echo "row conut in file: ".count($pureArrayList1)."\n";
                $sql1 = "INSERT INTO s1ap_link_down_db (start_time, end_time, alarm_name, ne_type, alarm_src, enodeb_code, duration) VALUES ";
                foreach($pureArrayList1 as $pureArray){
                    $sql1.= "(";
                    $sql1.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql1.= "),";
                }
                // Trim last comma.
                $sql1 = rtrim($sql1, ",");
                //print_r($sql1);
                //echo "\n\n\n\n\n";
                // It's ready to insert.
                $start = microtime(true);
                query($sql1);
                $finish_second = microtime(true)-$start;
                echo "Finished time: $finish_second Sec. \n"; 
            }

            foreach($fileList as $file){
                
                // Get pure file.
                echo "Feed file: $file \n";
                $pureArrayList1 = insertVUSN($file);
                echo "row conut in file: ".count($pureArrayList1)."\n";
                $sql = "INSERT INTO s1ap_link_down_db (start_time, end_time, alarm_name, ne_type, alarm_src, enodeb_code, duration) VALUES ";
                foreach($pureArrayList1 as $pureArray){
                    $sql.= "(";
                    $sql.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                    $sql.= "),";
                }
                // Trim last comma.
                $sql = rtrim($sql, ",");
                //print_r($sql);
                //echo "\n\n\n\n\n";
                // It's ready to insert.
                $start = microtime(true);
                query($sql);
                $finish_second = microtime(true)-$start;
                echo "Finished time: $finish_second Sec. \n"; 
            }
        


