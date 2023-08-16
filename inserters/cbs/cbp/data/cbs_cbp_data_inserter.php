<?php

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

function dateLog(){
    return date('Y-m-d H:i:s')." => ";
}

function logPrinter($text){
    if(LOG_IN_INSERTER){
        echo $text;
    }
}

/**
 * get file path and get sql command for it.
 * 
 * @param string $file path of file to feed.
 * @return array an insertable array to database, [cbp_id, cerrc_id, count, cdr_date, cdr_time]
 */

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
            $cbs_cbp_data_err_code = query("SELECT cerrc_id, cerrc_code FROM cbs_cbp_data_err_code")->result;
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
            $cbs_cbp_data_rg_code = query("SELECT rg_id, rg_code FROM cbs_cbp_data_rg_code")->result;
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
        $cdr_date_time = $cdr_date_time+(60*60*3.5);
        $cdr_date_time =  date('Y-m-d H:i', $cdr_date_time);

        // Calc log_id
    
        $uniqKey = md5($cdr_date_time.$cerrc_id.$regionId.$cbp_id,$rg_id);
        $results[] = [$uniqKey, $cdr_date_time, $cerrc_id, $regionId, $cbp_id, $rg_id, $count];
   
    }
    //print_r($results);
    //echo "\n\n\n\n";
    return $results;
}

// Get param file path to detect log files that need to read.
$paramFilePath = $argv[1];

// Get logs directory to store logs.
$paramFilePathParts = explode("/", $paramFilePath);
array_pop($paramFilePathParts);
array_pop($paramFilePathParts);
$directory =  implode("/", $paramFilePathParts)."/temp-files/";

$filesWithoutExtensionCSV = file($paramFilePath)[0];
$filesWithoutExtension = explode(",", $filesWithoutExtensionCSV);

foreach($filesWithoutExtension as $fileWithoutExtension){
    $indexFilePath = $directory.$fileWithoutExtension.".ndx";
    $file = $directory.$fileWithoutExtension.".tmp";

    // Get pure file.
    logPrinter("\n".dateLog()."Feed file: $fileWithoutExtension \n");
    $pureArrayList = insertFailedCDR($file);
    logPrinter(dateLog()."Row conut in file: ".count($pureArrayList)."\n");
    shell_exec("rm -rf $file"); 
    shell_exec("find $directory -name \"*.ndx\" -type f -cmin +600 -exec rm -rf {} \; ");
    /**
     * Make sql query.
     * 
     * Sort pureArrayList by md5 hash.
     * Get hash and save it in uniqKeyList array.
     * Remove duplicated hash.
     * 
     * The count of $uniqKeyListUniqed show insert query 
     * Count that must be executed.
     */
    usort($pureArrayList, function($a, $b){
        return strcmp($a[0], $b[0]);
    });
    $uniqKeyList = array_column($pureArrayList, 0);
    $uniqKeyListUniqed = array_unique($uniqKeyList);

    
    /**
     * Make map.
     * List [a, b, c]
     * a -> start index in pureArrayList.
     * b -> end index in pureArrayList.
     * c -> item count, b-a is result.
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
    $map[count($map)-1][1] = count($pureArrayList)-1;

    // Calc col c that described in top.
    foreach($map as &$m){
        $m[2] = $m[1]-$m[0];
    }
    
    // Sort map by c feild.
    usort($map, function($a, $b){
        $_a = (int) $a[2];
        $_b = (int) $b[2];
        if($_a < $_b){
            return -1;
        }
        if($_a > $_b){
            return 1;
        }
        return 0;
    });

    /**
     * Make none-confilict query.
     */
    $mapLastIndex = count($map)-1;
    $biggestHashListIndex = $map[$mapLastIndex][2];

    $queryCountToExecute = 0;
    $queryCountToSuccess = 0;
    $queryCountToFail = 0; 
    
    for($i=$biggestHashListIndex, $k=1; $i>=0; $i--, $k++){
        $csvOfQuery = "";
        $itemCount = 0;
        for($j=$mapLastIndex; $j>=0; $j--){
            if($map[$j][2] >= $biggestHashListIndex-$i){
                $index = $map[$j][0]+$biggestHashListIndex-$i;
                $item = $pureArrayList[$index];
                array_shift($item);
                $csvOfQuery.= "(".TypeConvertorHelper::arrayToCSV($item, true)."),";
                $itemCount++;
            }
        }
        $csvOfQuery = rtrim($csvOfQuery, ",");
        $sql = "INSERT INTO cbs_cbp_data_err_code_log VALUES $csvOfQuery 
        ON CONFLICT ON CONSTRAINT cbs_cbp_data_err_code_log_uniq_cons DO UPDATE
        SET e_count = cbs_cbp_data_err_code_log.e_count+excluded.e_count;
        COMMIT;";
        
        /**/ 
        /**
        * It's ready to insert.
        * Execute insert operation and calc time of it.
        */
        $start = microtime(true);
        //print_r($sql);
        //echo "\n\n\n\n\n";
        $resultQuery = query($sql);
        $finish_second = microtime(true)-$start;
        //logPrinter(dateLog().$csvOfQuery."\n");
        //logPrinter(dateLog()."($k / ".($biggestHashListIndex+1)." | $itemCount) Finished time: ".round($finish_second, 4, PHP_ROUND_HALF_UP)." Sec. \n");
        
        // Statistic of query operation
        $queryCountToExecute++;
        if($resultQuery->status){
            $queryCountToSuccess++;
        }else{
            $queryCountToFail++;
        }
    }
    $querySR = ($queryCountToSuccess/$queryCountToExecute)*100;
    echo "query_result: $queryCountToExecute,$queryCountToSuccess,$queryCountToFail,$querySR \n";
  
    /*echo "\n\n\n";
    echo "queryCountToExecute: ".$queryCountToExecute. "\n";
    echo "queryCountToSuccess: ".$queryCountToSuccess. "\n";
    echo "queryCountToFail: ".$queryCountToFail. "\n";
    */

    // Update indexes and etc.
    $setting = explode(",", file($indexFilePath)[0]);
    $setting[4] = 1;
    // Write file setting in index file.
    $fp = fopen($indexFilePath, 'wr');
    fwrite($fp, TypeConvertorHelper::arrayToCSV($setting));
    fclose($fp);
}

 