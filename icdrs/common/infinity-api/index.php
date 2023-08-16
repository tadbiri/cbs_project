<?php

// Load the method file.
require_once dirname(__DIR__, 2) . "/config/localconfig.php";
require_once dirname(__DIR__, 1) . "/amchart/chart/jsonhelperfunctions.php";
require_once dirname(__DIR__, 1) . "/amchart/chart/cachechart.php";

// Add request to log.
//$dump_file = fopen(dirname(__FILE__, 1) . "/dump.log", "a");
//fwrite($dump_file, print_r($_SERVER['QUERY_STRING'], true)."\n");
//fclose($dump_file);

ini_set('memory_limit','8192M');
// Set header to serve response as a json type.
header("content-type: application/json; charset=utf-8");

/**
 * Check for mandatory fields.
 */



$fromTimestamp = $_GET['from'] ?? null;
if (is_null($fromTimestamp) || trim($fromTimestamp) == '') {
    header("HTTP/1.1 400 Bad Request, 'from' not set.");
    exit;
}
if (!preg_match("/^[0-9a-zA-Z]+(,[0-9a-zA-Z]+)*$/", $fromTimestamp)) { 
    header("HTTP/1.1 400 Bad Request, 'from' set but not in a valid pattern.");
    exit;        
}
// Remove seconds, millisecond from timestamp
$fromTimestamp = $fromTimestamp / 1000;
// Engage app time-shift.
$fromTimestamp = $fromTimestamp - TIMESTAMP_SHIFT;
$fromTimestamp = strtotime(date('Y-m-d H:i:00', $fromTimestamp));



$toTimestamp = $_GET['to'] ?? null;
if (is_null($toTimestamp) || trim($toTimestamp) == '') {
    header("HTTP/1.1 400 Bad Request, 'to' not set.");
    exit;
}
if (!preg_match("/^[0-9a-zA-Z]+(,[0-9a-zA-Z]+)*$/", $toTimestamp)) { 
    header("HTTP/1.1 400 Bad Request, 'to' set but not in a valid pattern.");
    exit;        
}

// Remove seconds, millisecond from timestamp
$toTimestamp = $toTimestamp / 1000;
// Engage app time-shift.
$toTimestamp = $toTimestamp - TIMESTAMP_SHIFT;
$toTimestamp = strtotime(date('Y-m-d H:i:00', $toTimestamp));



/**
 * Data type string.
 * The requested cache dir name.
 */
$cacheName = $_GET['cache-name'] ?? null;
if (is_null($cacheName) || trim($cacheName) == '') {
    header("HTTP/1.1 400 Bad Request, 'cache-name' not set.");
    exit;
}
$cacheName = strtolower($cacheName);
/**
 * Validate requested cache name.
 * Get all cache dirs.
 * 
 */
$directories = glob(CACHE_POOL_DIR . '/*', GLOB_ONLYDIR);
$directories = array_map(function ($directory) {
    return strtolower(basename($directory));
}, $directories);

if(!in_array($cacheName, $directories)){
    header("HTTP/1.1 404 Not Found, requested 'cache-name' not found: '".$cacheName."'");
    exit;
}

/**
 * Get requested entities list.
 * 
 * Data type string
 * accepted patterns: 
 * 1 or c1
 * 1,2 or c1,c2
 * {1,2} or {c1,c2}
 */
$requestedEntities = [];
$entity = $_GET['entity'] ?? null;
if (is_null($entity) || trim($entity) == '') {
    header("HTTP/1.1 400 Bad Request, 'entity' not set.");
    exit;
}
// Validate c1,
if(!preg_match("/^[A-Za-z0-9]*$/", $entity) && $entity != '__ALL__'){
    
    // Check for '{...}' pattern, remove '{' and '}'.
    if($entity[0] == "{" || $entity[strlen($entity)-1] == "}"){
        $entity = substr($entity, 1, strlen($entity)-2); 
    }
    // Validate c1,c2
    if (!preg_match("/^[0-9a-zA-Z]+(,[0-9a-zA-Z]+)*$/", $entity)) { 
        header("HTTP/1.1 400 Bad Request, 'entity' set but not in a valid pattern.");
        exit;        
    }

    // Convert all to lower case.
    $requestedEntities = array_map(function($item){ return strtolower($item);}, explode(",", $entity));

}else{
    $requestedEntities = [strtolower($entity)];
}


// Get existed entities.  
$existedEntities = CacheChart::fetchEntityFromCacheFiles($cacheName);
$existedEntities = array_map(function($item){ return strtolower($item);}, $existedEntities);

// Check for exception all.
if(count($requestedEntities) == 1 && $requestedEntities[0] == '__all__'){
    $requestedEntities = $existedEntities; 
}

// validate requested entities.
foreach($requestedEntities as $re){
    if(!in_array($re, $existedEntities)){
        header("HTTP/1.1 404 Not Found, requested 'entity' not found: '".$re."' ");
        exit;
    }
}




/**
 * An optional setting.
 * Based per minute.
 * Just check that in case it set, it's be a number.
 */
$timeShift = $_GET['time-shift'] ?? null;
if (!is_null($timeShift) && trim($timeShift) != '' && trim($timeShift) != '0') {

    // Check to just get a integer number.
    if (!preg_match("/^[0-9]*[1-9][0-9]*$/", $timeShift)) {
        header("HTTP/1.1 400 Bad Request, 'time-shift' must be pure integer.");
        exit;
    }
}
$timeShiftBasedSecond = 0;
if(!is_null($timeShift)){
    $timeShiftBasedSecond = $timeShift * 60;
}
/**
 * In case that time-shift configured, Engage it in time.
 * So cache files always fetch based time-shift configure.
 */
$toDate = date('Y-m-d', $toTimestamp-$timeShiftBasedSecond);
$fromDate = date('Y-m-d', $fromTimestamp-$timeShiftBasedSecond);

$foundedFiles = CacheChart::getCacheFileList($cacheName, $fromDate, $toDate);

// Hold main result.
$result = [
    'channel_dump'=>[
        'from-with-shift'=>date('Y-m-d H:i:00', $fromTimestamp-$timeShiftBasedSecond),
        'to-with-shift'=>date('Y-m-d H:i:00', $toTimestamp-$timeShiftBasedSecond),

        'from-show'=>date('Y-m-d H:i:00', $fromTimestamp),
        'to-show'=>date('Y-m-d H:i:00', $toTimestamp),
    ],
    'feeds' => [],
];


/**
 * To generate point counts and datetime.
 * Iterate per point.
 */
$periodPerMinute = ($toTimestamp-$fromTimestamp) / 60;
for($i = $periodPerMinute; $i>=0; $i--){
    
    /**
     * In case that time-shift configured, Engage it in time.
     */
    $currentTimestamp = $toTimestamp-($i*60);
    $fetchTimestamp = $currentTimestamp - $timeShiftBasedSecond;  

    $fetchFile = $cacheName."-".date('Ymd', $fetchTimestamp).".cbsc";
    
    /**
     * Read data from cache file.
     */
    $currentEntity = [];
    if(in_array($fetchFile, $foundedFiles)){

        // Get line index for current timestamp 
        $_lineIndex = CacheChart::getFileLineIndexByTimestamp($fetchTimestamp);

        /**
         * Get current line.
         * Fetch data section from line.
         * Make a list like based founded entity.
         */
        $_currentCacheFileLines = file(CACHE_POOL_DIR.$cacheName."/".$fetchFile);
        $_currentLineSeparated = explode(',', $_currentCacheFileLines[$_lineIndex]);
        
        $entities = array_slice($_currentLineSeparated, ENTITY_START_INDEX);
        foreach($entities as $e){
            $item = explode(":", $e);
            $currentEntity[strtolower($item[0])] = $item[1];
        }
    }
    
    // Calculate point.
    $point = [
        // currentTimestamp just applied in show point. 
        '__DATETIME__' => date_format(date_timestamp_set(new DateTime(), $currentTimestamp), 'c'),
    ];
    foreach($requestedEntities as $ee){
        
        // Cast cache pure data to useable data.
        $value = null;
        if(!empty($currentEntity)){
            $value = trim(preg_replace('/\s\s+/', ' ', $currentEntity[$ee]));
            if($value == 'NaN'){
                $value = null;
            }else{
                $value = (int) $value;
            }
        }
        $point[$ee] = $value;
    }

    // Add calculated point to feed.
    $result['feeds'][] = $point;
}

echo json_encode($result);