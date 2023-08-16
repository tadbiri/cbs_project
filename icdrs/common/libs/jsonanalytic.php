<?php
// Load configure file and needed libs.
require_once dirname(__DIR__, 2)."/config/localconfig.php";

require_once dirname(__DIR__, 1)."/amchart/chart/charthelperfunctions.php";
require_once dirname(__DIR__, 2)."/config/chartaveragetype.php";

require_once dirname(__DIR__, 1)."/amchart/chart/trafficanalytichelper.php";
require_once dirname(__DIR__, 2)."/config/chartaveragetypeconfig.php";


// Set header to serve response as a json type.
//header("content-type: application/json; charset=utf-8");

// Get all chart name list.
$chartNameList = array_keys($ChartAverageTypeConfig);

$charts = $chartNameList;
$charts = array_map(function($chart){
    return strtolower($chart);
}, $charts);

// Validate chart.
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

// Everything is ok.

// Get related chartAverageType object by type.
$typeObject = ChartAverageType::getByName($type);

// Find period from type.
$periodBasedMinuteToShow = $typeObject['PeriodBasedMinuteToShow'];
$periodBasedSecodToShow = $periodBasedMinuteToShow*60;


// Get expected period by type.
$endTS = get_end_timestamp();
$startTS = $endTS - $periodBasedSecodToShow; 

/**
 * In here check that related file is existed or not.
 * Exception of not any file exist.
 * 
 * Get whole line from file.
 */
$path = dirname(__DIR__, 2)."/cachepool/analytics/$chart.cbsc";
if(!file_exists($path)){
    header("HTTP/1.1 200 ");
    echo json_encode([
        'status'=>'ok',
        'http-code'=>'200',
        'dataset'=>null,
    ]);
    exit;
}

$lines = file($path);
$lineCount = count($lines)-1;
$firstLineTS = explode(",", $lines[0])[0];
$lastLineTS = explode(",", $lines[$lineCount])[0];

// Fix periods.
if($endTS > $lastLineTS){ 
    $endTS = $lastLineTS;
}

if($startTS < $firstLineTS){
    $startTS = $firstLineTS;
}

if($startTS >= $endTS){
    header("HTTP/1.1 200 ");
    echo json_encode([
        'status'=>'ok',
        'http-code'=>'200',
        'dataset'=>null,
    ]);
    exit;
}

//echo "After fix: \n";
//echo "start: ".date('Y-m-d H:i:s', $startTS)."\n";
//echo "end: ".date('Y-m-d H:i:s', $endTS)."\n\n";

// Get an instance of Chart class to get entities config.
$exactChartName = $chartNameList[array_search($chart, $charts)];
require_once dirname(__DIR__, 2)."/api/".$exactChartName.".php";
$chartClassObject = new $exactChartName();

if($chartClassObject->deleteCoefficient == null){
    header("HTTP/1.1 500 ");
    echo json_encode([
        'status'=>'error',
        'http-code'=>'500',
        'description'=>"The 'deleteCoefficient' property not defined in API class."
    ]);
    exit;

}

// This variable hold data for each entity.
$DataList = [];
foreach($chartClassObject->entities as $entity){
    if(!isset($entity['analytic'])){
        header("HTTP/1.1 500 ");
        echo json_encode([
            'status'=>'error',
            'http-code'=>'500',
            'description'=>"'analytic' Configure not defined in API class."
        ]);
        exit;
    }
    $DataList[$entity['label']] = [];
    $DataList[$entity['label']]['Data'] = [];
    $DataList[$entity['label']]['Entity'] = $entity;
}

echo "line first: ".explode(",", $lines[0])[1]."<br>";
echo "line last: ".explode(",", $lines[$lineCount])[1]."<br><br>";

echo "firstLineTS: ".date("Y-m-d H:i:s", $firstLineTS)." <br>";
echo "lastLineTS: ".date("Y-m-d H:i:s", $lastLineTS)." <br><br>";

echo "start: ".date('Y-m-d H:i:s', $startTS)."<br>";
echo "end: ".date('Y-m-d H:i:s', $endTS)."<br><br>";


echo "<br><br><br>";

echo ((strtotime("2022-07-12 00:10:00")-$firstLineTS)/60)."<br>";
$a = (strtotime("2022-07-12 00:10:00")/60);
$b = ($firstLineTS/60);
echo $a-$b;
echo "<br><br><br>";

for($i=1; $i<count($lines); $i++){
    $ts1 = explode(",", $lines[$i-1])[0];
    $ts2 = explode(",", $lines[$i])[0];

    //echo date('Y-m-d H:i:s', $ts1)."<Br>";

    if($ts2-$ts1 != 60){
        echo "not <br>";
        echo $lines[$i-1]."<br>";
        echo $lines[$i];
        //exit;
        echo "<br>";
    }

}

// Caculate indexes to read file.
$fileStartIndex = ($startTS/60)+($firstLineTS/60);
$fileEndIndex = ($endTS/60)+($firstLineTS/60);



echo "<br>";
echo "fileStartIndex: ".$fileStartIndex."\n";
echo "fileEndIndex: ".$fileEndIndex."\n";


echo "<pre>";
print_r(count($lines));
echo "</pre>";
echo "<pre>";
print_r($lines[0]);
echo "</pre>";
echo "<pre>";
print_r($lines[count($lines)-1]);
echo "</pre>";

// Iterate on each line.
for($i=$fileStartIndex; $i<=$fileEndIndex; $i++){
    // Process line.
    $lineParts = explode(",", $lines[$i]);
    
    echo $lines[$i]." <br>";

    $pointTS = $lineParts[0];
    $pointDatetime = $lineParts[1];
    $Entities = array_slice($lineParts, 2);

    $count = 0;

    foreach($Entities as $Entity){      
        $EntityParts = explode("<>", $Entity);
        $EntityInfo = explode(":", $EntityParts[0]);
        $standardDeviation = (float) $EntityParts[1];
        $values = explode("'", $EntityParts[2]);
        $values = array_map(function($value){
            $value = (int) $value;
            return $value;
        }, $values);


        $valuesAvg = TrafficAnalyticHelper::getAverageOfList($values);

        $clearedValues = TrafficAnalyticHelper::clearListByStandardDeviation($values, $standardDeviation, $valuesAvg, $chartClassObject->deleteCoefficient);


        $entityName = $EntityInfo[0];
        $entityValue = (int) $EntityInfo[1];
        if(count($clearedValues) == 0){
            $DataList[$entityName]['Data'][] = 'NaN';
            continue;
        }
        $clearedValuesAvg = TrafficAnalyticHelper::getAverageOfList($clearedValues);


        $percent = TrafficAnalyticHelper::getPercentCurrentPoint($entityValue, $clearedValuesAvg);

        $DataList[$entityName]['Data'][] = $percent;

    }
}

exit;

// Make final data set.
$dataset = [];
$dataset['xAxis']['startDateTime'] = date('Y-m-d H:i:s', $startTS);
$dataset['xAxis']['endDateTime'] = date('Y-m-d H:i:s', $endTS);
$dataset['xAxis']['refreshLastPointBasedMinute'] = $chartClassObject->refreshLastPointBasedMinute;
$dataset['xAxis']['pointCount'] = ($fileEndIndex-$fileStartIndex+1)*-1;

$dataset['entities'] = [];
foreach($DataList as $entityName => $Data){
    $entityDataset = [];
    $entityDataset['label'] = $entityName;
    $entityDataset['description'] = $Data['Entity']['analytic']['description'];
    $entityDataset['colorCode'] = $Data['Entity']['analytic']['colorCode'];
    $entityDataset['data'] = $Data['Data'];

    $dataset['entities'][] = $entityDataset;
}

echo json_encode([
    'status'=>'ok',
    'http-code'=>'200',
    'dataset'=>$dataset,
]);