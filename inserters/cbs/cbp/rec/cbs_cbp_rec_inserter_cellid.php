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

//cell id 

 function cell_id($file){
     global $cbs_cbp_cellid;

    $uniqCDRs = shell_exec("cat $file | awk -F\"|\" '{if ($527 == \"98\") print $501,$525}' | sort -k1 -k2 | uniq");   
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

    }
    $f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }
    //return $results;
}




$cityList = getDirList('/cbshome/cdr_analysis/data/cbs/cbp/rec');
//$city = '/cbshome/cdr_analysis/data/cbs/cbp/rec/mashhad';
while(true) {
    foreach($cityList as $city){
        $dateDirList = getDirList($city);

        foreach($dateDirList as $dateDir){
            
            $fileList = getFilelist($dateDir);
            foreach($fileList as $file){
                if (filesize($file) == 0 ){continue;}
                $pureArrayList = cell_id($file);
            }
        }
    }
    sleep(1);
} 