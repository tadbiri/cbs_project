<?php

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

/**
 * see_id
 * see_code
 */
$cbs_see_name = query("SELECT * FROM cbs_see_name")->result;

/**
 * serrc_id
 * serrc_code
 */
$cbs_see_voice_err_code = query("SELECT serrc_id, serrc_code FROM cbs_see_voice_err_code")->result;
// end cache.


// Functions for insert not-found data.
function newSubkeyClass($subkey_code, $class_code){
    $subkey_class_desc = 'new-item';
    $query = "INSERT INTO cbs_see_voice_subkey_class (subkey_code, class_code, subkey_class_desc) VALUES (?, ?, ?)";
    return query($query, [$subkey_code, $class_code, $subkey_class_desc]);
}

function newErrCode($serrc_code){
    $serrc_desc = "new-item";
    $query = "INSERT INTO cbs_see_voice_err_code (serrc_code, serrc_desc) VALUES (?, ?)";
    return query($query, [$serrc_code, $serrc_desc]);
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
 * @return array an insertable array to database, [see_id, subkey_class_id, serrc_id, count, cdr_date, cdr_time]
 */
function insertFailedCDR($file){
    global $cbs_see_voice_err_code, $cbs_see_name;
    $seeCode = shell_exec("stat $file | head -n 1 | cut -d \".\" -f 3");
    $seeCode = trim($seeCode);
    $index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_name, ['see_code', $seeCode]);
    $see_id = $cbs_see_name[$index]['see_id'];
    $regionId = $cbs_see_name[$index]['region_id'];

    $uniqCDRs = shell_exec("cat $file | awk -F \"|\" '{ print $4,$8,$2}' | sort -k1 -k2 -k3 -n | uniq -f1 -f2 -c");
    $uniqCDRs = explode("\n", $uniqCDRs);

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
        // 7
        $serrc_id = '0';
        /*$index = TypeConvertorHelper::getIndexOfKeyValueInArray($cbs_see_voice_err_code, ['serrc_code', $ERROR_CODE]);
        // Check for find match.
        if($index == -1){
            // Not any match find, so make it and use it. 
            $serrc_id = newErrCode($ERROR_CODE);

            //echo "Make $ERROR_CODE in new error \n";
        }else{
            $serrc_id = $cbs_see_voice_err_code[$index]['serrc_id'];
        }*/
        $cdr_date = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3]."-".$dateTime[4].$dateTime[5]."-".$dateTime[6].$dateTime[7];
        $cdr_time = $dateTime[8].$dateTime[9].":".$dateTime[10].$dateTime[11];
        $cdr_date_time = $cdr_date." ".$cdr_time;

        //echo "Expected date time : $cdr_date_time \n";
        //$results[] = [$see_id, $subkey_class_id, $serrc_id, $count, $cdr_date, $cdr_time];
        // Calc log_id
        //$serrc_log_id = str_pad($see_id, 2, '0', STR_PAD_LEFT).str_pad($regionId, 2, '0', STR_PAD_LEFT).str_pad($serrc_id, 2, '0', STR_PAD_LEFT).str_pad($subkey_code, 2, '0', STR_PAD_LEFT).str_pad(strtotime($cdr_date_time), 10, '0', STR_PAD_LEFT);
        //echo "Expected primary key: $serrc_log_id \n";

        $uniqKey = md5($cdr_date_time.$ERROR_CODE.$regionId.$see_id);

        $results[] = [$uniqKey, $cdr_date_time, $ERROR_CODE, $regionId, $see_id, $subkey_code, $count];
        
    }

    // Clear content of file after calc.
    /*$f = @fopen("$file", "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }*/
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
        $sql = "INSERT INTO cbs_see_voice_err_code_log VALUES $csvOfQuery 
                ON CONFLICT ON CONSTRAINT cbs_see_voice_err_code_log_uniq_cons DO UPDATE
                SET e_count = cbs_see_voice_err_code_log.e_count+excluded.e_count;";
        
        /**
        * It's ready to insert.
        * Execute insert operation and calc time of it.
        */
        $start = microtime(true);
        //print_r($sql);
        //echo "\n\n\n\n\n";
        query($sql);
        $finish_second = microtime(true)-$start;
        //logPrinter(dateLog().$csvOfQuery."\n");
        logPrinter(dateLog()."($k / ".($biggestHashListIndex+1)." | $itemCount) Finished time: ".round($finish_second, 4, PHP_ROUND_HALF_UP)." Sec. \n");
    }

    // Update indexes and etc.
    $setting = explode(",", file($indexFilePath)[0]);
    $setting[4] = 1;
    // Write file setting in index file.
    $fp = fopen($indexFilePath, 'wr');
    fwrite($fp, TypeConvertorHelper::arrayToCSV($setting));
    fclose($fp);
}