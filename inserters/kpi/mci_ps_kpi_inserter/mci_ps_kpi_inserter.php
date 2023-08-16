<?php

require_once "config_cdr.php";

date_default_timezone_set("Asia/Tehran");

ini_set('memory_limit','50192M');
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

function getFilelist1($path){
    $_res = [];
    $files = scandir($path);   

    foreach($files as $file){
        $fullPath = $path."/".$file;
        if(is_file($fullPath) && (explode(".", $fullPath)[count(explode(".", $fullPath))-1])[0] == "_"){
            echo "file1_list: $file\n\n";
            if($file == "." || $file == '..'){continue;}
            if (filesize($fullPath) == 0 ){continue;}
                $_res[] = $fullPath;
        }
    }
    
    return $_res;
}

function getFilelist($path){
    $_res = [];
    $files = scandir($path);   

    foreach($files as $file){
        $fullPath = $path."/".$file;
        if(is_file($fullPath) && explode(".", $fullPath)[count(explode(".", $fullPath))-1] == "gz"){
            if($file == "." || $file == '..'){continue;}
            if (filesize($fullPath) == 0 ){continue;}
                $_res[] = $fullPath;
        }
    }
    
    return $_res;
}


function psinserter($file){

    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ if($13 == \"mcinet\") print $3,$1,$4,$5,$6,$7,$8,$9,$10}'");
   //echo "awk: $uniqCDRs \n";
   $uniqCDRs = explode("\n", $uniqCDRs);
   //print_r($uniqCDRs);
   //echo "\n\n\n";
   // an array to hold results.
   $results = [];
   foreach($uniqCDRs as $uniqCDR){
       try {

       $uniqCDR = trim(preg_replace('/\t+/', '', $uniqCDR));
       //echo "$uniqCDR \n\n\n";
       $uniqCDR_Parts = explode(" ", $uniqCDR);
       if(count($uniqCDR_Parts) != 9){continue;}
       $date_time = $uniqCDR_Parts[0];
       $msisdn = $uniqCDR_Parts[1];
       $protocol = $uniqCDR_Parts[2];
       $pri_ip = $uniqCDR_Parts[3];

       if(is_null($pri_ip)){
            $pri_ip = 0;
       }else{
            $pri_ip = hexdec($pri_ip);
            $pri_ip = long2ip($pri_ip);
       }
       
       $srs_port = $uniqCDR_Parts[4];
       $pub_ip = $uniqCDR_Parts[5];
       if(is_null($pub_ip)){
             $pub_ip = 0;
        }else{
             $pub_ip = hexdec($pub_ip);
             $pub_ip = long2ip($pub_ip);
        }

       $d_port = $uniqCDR_Parts[6];
       $des_ip = $uniqCDR_Parts[7];
        if(is_null($des_ip)){
             $des_ip = 0;
        }else{
             $des_ip = hexdec($des_ip);
             $des_ip = long2ip($des_ip);
        }

       $des_port = $uniqCDR_Parts[8];

       $date_time = date('Y-m-d H:i:s', $date_time);
    



        $results[] = [$date_time,$msisdn,$protocol,$pri_ip,$srs_port,$pub_ip,$d_port,$des_ip,$des_port];
        //print_r ($results);
        //echo "\n";
    }
    catch(exception $e) {}
    }
    return $results;
    
}

//$dataDir = getenv("INS_DATA_DIR");
$dataDir = "/cbshome/cdr_analysis/ps";
define('FEED_LOG_DIR', "$dataDir");
//while(true) {
    //foreach($cityList as $city){
        //$dateDirList = getDirList($city);

        //echo "City: $city \n";
        //print_r($dateDirList);
        //echo "\n\n";

        //($dateDirList as $dateDir){
            
            $fileList = getFilelist(FEED_LOG_DIR);

            //echo "Date Dir: $dateDir \n";
            //print_r($fileList);
            //echo "\n\n";

            foreach($fileList as $file){
                $start = microtime(true); 
                echo "file: $file \n";
                shell_exec("gunzip $file");
                $file = explode(".",$file)[0].".csv";
                shell_exec("split -l 200000 -d $file $file.'_'");
                
                $fileList1 = getFilelist1(FEED_LOG_DIR);
                print_r($fileList1);
                echo "file1\n\n\n";
               
                foreach($fileList1 as $file1){
                    echo "file1: $file1 \n\n\n";
                    $pureArrayList = psinserter($file1);
                    print_r($pureArray);
                    echo "\n\n\n";
                    unlink($file1);
                    $finish_second = microtime(true)-$start;
                    echo "awk Time--> $finish_second \n";
                    echo "row conut in file: ".count($pureArrayList)."\n";
                    $sql1 = "INSERT INTO  ps_log(date_time,msisdn,protocol,pri_ip,srs_port,pub_ip,d_port,des_ip,des_port) VALUES ";
                    foreach($pureArrayList as $pureArray){
                        $sql1.= "(";
                        $sql1.= TypeConvertorHelper::arrayToCSV($pureArray, true);
                        $sql1.= "),";
                    }
                    // Trim last comma.
                    $start = microtime(true);
                    $sql1 = rtrim($sql1, ",");

                    // Add statement to update in duplicate primary key.

                    // It's ready to insert.
                    //$start = microtime(true);
                    query($sql1);
                    $finish_second = microtime(true)-$start;
                    echo "query Time--> $finish_second \n";
                    //$finish_second = microtime(true)-$start;
                    $pureArrayList = 0;
                }
        
        unlink($file);
        }
    //}
    sleep(1);
//}

