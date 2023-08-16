<?php
// Load configure file and needed libs.

require_once dirname(__DIR__, 2)."/config/localconfig.php";

require_once FullCommonPath."/amchart/chart/cachechart.php";
require_once FullCommonPath."/amchart/chart/charthelperfunctions.php";
require_once dirname(__DIR__, 2)."/config/chartaveragetype.php";
require_once dirname(__DIR__, 2)."/config/chartaveragetypeconfig.php";

// Set header to serve response as a json type.
header("content-type: application/json; charset=utf-8");


// Get all chart name list.
$chartNameList = array_keys($ChartAverageTypeConfig);

// Validate that 'chart' specificated or not.
$charts = $chartNameList;
$charts = array_map(function($chart){
    return strtolower($chart);
}, $charts);
if(!isset($_GET['chart']) || empty($_GET['chart'])){
    header("HTTP/1.1 400 ");
    echo json_encode([
        'status'=>'error',
        'http-code'=>'400',
        'description'=>'chart not set!'
    ]);
    exit;
}
$chart = strtolower($_GET['chart']);
if(!in_array($chart, $charts)){
    header("HTTP/1.1 404 ");
    echo json_encode([
        'status'=>'error',
        'http-code'=>'404',
        'description'=>'chart not found!'
    ]);
    exit;
}

// Validate that 'type' specificated or not.
$chartAverageTypeList = ChartAverageType::getAll();
$types = array_column($chartAverageTypeList, 'name');
$types = array_map(function($type){
    return strtolower($type);
}, $types);
if(!isset($_GET['type']) || empty($_GET['type'])){
    header("HTTP/1.1 400 ");
    echo json_encode([
        'status'=>'error',
        'http-code'=>'400',
        'description'=>'type not set!'
    ]);
    exit;
}
$type = strtolower($_GET['type']);
if(!in_array($type, $types)){
    header("HTTP/1.1 404 ");
    echo json_encode([
        'status'=>'error',
        'http-code'=>'404',
        'description'=>'type not found!'
    ]);
    exit;  
}

// everything is ok.

// Get related chartAverageType object by type.
$typeObject = ChartAverageType::getByName($type);

// Find period from type.
$periodBasedMinuteToShow = $typeObject['PeriodBasedMinuteToShow'];
$periodBasedSecodToShow = $periodBasedMinuteToShow*60;

$end_timestamp = get_end_timestamp();
$start_timestamp = $end_timestamp - $periodBasedSecodToShow; 

$endDate = date('Y-m-d', $end_timestamp);
$startDate = date('Y-m-d', $start_timestamp);

// Get files that related with selected chart and period date.
$files = CacheChart::getCacheFileList($chart, $startDate, $endDate);

// Exception of not any file exist.
if(empty($files)){
    echo json_encode([
        'status'=>'ok',
        'http-code'=>'200',
        'dataset'=>null,
    ]);
    exit;
}

// Reverse files to Z to A, that mean is first file is last file.
$files = array_reverse($files);

// Find first file.
$firstFile = $files[count($files)-1];

// Hold sorted final cache data.
$caches = [];
$firstPointTimestamp = null; 
// Iterate on files.
foreach($files as $f){
    // Get lines of current file.
    $fileLines = file(CacheChart::getFullPath($f));
    /**
     * Make cache list,
     * cache is a variable that hold lines from cache files.
     */
    $indexes = CacheChart::getLineIndexes($f);
    // Check for find error in cache file.
    if($indexes->startFileIndex == -1){
        header("HTTP/1.1 404 ");
        echo json_encode([
            'status'=>'error',
            'http-code'=>'500',
            'description'=>"somthing happen, a bad cache file found! $f"
        ]);
        exit;
    }
    $lastLineIndex = $indexes->endFileIndex;
    $firstLineIndex = $indexes->startFileIndex;

    for($i=$lastLineIndex; $i>=$firstLineIndex; $i--){
        $pointTimestamp = explode(',', $fileLines[$i])[FILEINDEX_TIMESTAMP_INDEX];
        if($firstPointTimestamp == null){
            $firstPointTimestamp = $pointTimestamp;
        }
        $passedTimestamp = $firstPointTimestamp-$pointTimestamp;
        $passedMinute = floor($passedTimestamp/60);
        if($passedMinute >= $periodBasedMinuteToShow){
            break;
        }
        $caches[] = trim(preg_replace('/\s\s+/', ' ', $fileLines[$i]));
    }
}
$caches = array_reverse($caches);

// Get an instance of Chart class to get entities config.
$exactChartName = $chartNameList[array_search($chart, $charts)];
require_once dirname(__DIR__, 2)."/api/".$exactChartName.".php";
$chartClassObject = new $exactChartName();

// Remove last data item per config. 
$_chartMinuteShift = $chartClassObject->chartMinuteShift;
$caches = array_slice($caches, 0, (count($caches)-$_chartMinuteShift));

/**
 * Define final dataset object.
 * 
 * fetch period datatimes for chart.
 */
$dataset = [];
$firstItem = explode(',', $caches[0]);
$lastItem = explode(',', $caches[count($caches)-1]);

$startTimestamp = $firstItem[FILEINDEX_TIMESTAMP_INDEX];
$endTimestamp = $lastItem[FILEINDEX_TIMESTAMP_INDEX];

$showPeriodPerSecond = $endTimestamp-$startTimestamp;

$cacheItemCount = count($caches);


$dataset['xAxis']['startDateTime'] = date('Y-m-d H:i:s', $startTimestamp);
$dataset['xAxis']['endDateTime'] = date('Y-m-d H:i:s', $endTimestamp);
$dataset['xAxis']['refreshLastPointBasedMinute'] = $chartClassObject->refreshLastPointBasedMinute;
$dataset['xAxis']['pointCount'] = $periodBasedMinuteToShow;

// Calculate entities.
$dataset['entities'] = [];

foreach($chartClassObject->entities as $e){
    $dataset['entities'][] = [
        'label'=>$e['label'],
        'description'=>isset($e['description'])?$e['description']:'',
        'colorCode'=>$e['colorCode'],
        'data'=>[],
    ];
}

// Fill dataset by cache data.
for($i=0; $i<$cacheItemCount; $i++){
    $cacheList = explode(',', $caches[$i]);
    $entities = array_slice($cacheList, ENTITY_START_INDEX);
    for($j=0; $j<count($entities); $j++){
        $_value = (ENTITY_KEY_IS_EXIST)? explode(':', $entities[$j])[1] : $entities[$j];
        if($_value != 'NaN'){
            $_value = (float) $_value;
            if(floor($_value) == $_value){
                $_value = (int) $_value; 
            }
        }
        $dataset['entities'][$j]['data'][] = $_value;
    }
}

echo json_encode([
    'status'=>'ok',
    'http-code'=>'200',
    'dataset'=>$dataset,
]);