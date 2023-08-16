<?php

require_once "/cbshome/failedcdr_analysis/app/cbs_cdr_insert/database.php";
require_once "/cbshome/failedcdr_analysis/app/cbs_cdr_insert/type_convertor.php";

// Config section.
define('ERROR_CODE_ID', 100);
// End config.

/**
 * a query to get successErrorCode that existed between two dateTime and blonges to specific regionId.
 * 
 * @param dateTime $startTime
 * @param dateTime $endTIme
 * @param string $regionId
 * 
 * @return array [[dateTime, errorCount],...]
 */
function successChart($startTime, $endTime, $regionId){
    $query = "SELECT SUBSTRING_INDEX(cl.cdr_date_time,':',2) as 'dateTime', sum(cl.e_count) as 'errorCount' 
                FROM cbs_see_voice_err_code_log cl
                where cl.region_id = '" . $regionId . "'
                and cl.serrc_code = '" . ERROR_CODE_ID . "'
                and SUBSTRING_INDEX(cl.cdr_date_time,'.',1) BETWEEN '" . $startTime . "' AND '" . $endTime . "'
                group by SUBSTRING_INDEX(cl.cdr_date_time,':',2)";
    return query($query);
}

/**
 * a guery to get failedErrorCode that existed between two dateTime and blonges to specific regionId.
 * 
 * @param dateTime $startTime
 * @param dateTime $endTIme
 * @param string $regionId
 * 
 * @return array [[dateTime, errorCount], ...]
 */
function failedChart($startTime, $endTime, $regionId){
    $query = "SELECT SUBSTRING_INDEX(cl.cdr_date_time,':',2) as 'dateTime', sum(cl.e_count) as 'errorCount' 
                FROM cbs_see_voice_err_code_log cl
                where cl.region_id = '" . $regionId . "'
                and cl.serrc_code <> '" . ERROR_CODE_ID . "'
                and SUBSTRING_INDEX(cl.cdr_date_time,'.',1) BETWEEN '" . $startTime . "' AND '" . $endTime . "'
                group by SUBSTRING_INDEX(cl.cdr_date_time,':',2)";
    return query($query);
}

/**
 * a guery to get errorCodeList that existed between two dateTime and blonges to specific regionId.
 * 
 * @param dateTime $startTime
 * @param dateTime $endTIme
 * @param string $regionId
 * 
 * @return stringCSV 1,2,3
 */
function errorCodeList($startTime, $endTime, $regionId)
{
    $query = "SELECT cl.serrc_code as 'errorCode' 
                FROM cbs_see_voice_err_code_log cl
                where cl.region_id = '" . $regionId . "'
                and cl.serrc_code <> '100'
                and SUBSTRING_INDEX(cl.cdr_date_time,'.',1) BETWEEN '" . $startTime . "' AND '" . $endTime . "'
                group by cl.serrc_code";
    $object = query($query);
    $errorCodeList = TypeConvertorHelper::getKeyListFromArrayList($object, 'errorCode');
    return TypeConvertorHelper::arrayToCSV($errorCodeList, true);
}

/**
 * a guery to get errorCodeData.
 * 
 * 
 * @param dateTime $startTime
 * @param dateTime $endTIme
 * @param string $regionId
 * @param stringCSV $errorCodeCSV
 * 
 * @return array [[dateTime, errorCount, errorCode], ...]
 */
function errorCodeData($startTime, $endTime, $regionId, $errorCodeCSV){
    $query = "SELECT SUBSTRING_INDEX(cl.cdr_date_time,':',2) as 'dateTime',  sum(cl.e_count) as 'errorCount', cl.serrc_code as 'errorCode'
                FROM cbs_see_voice_err_code_log cl
                where cl.region_id = '" . $regionId . "'
                and cl.serrc_code IN (" . $errorCodeCSV . ")
                and SUBSTRING_INDEX(cl.cdr_date_time,'.',1) BETWEEN '" . $startTime . "' AND '" . $endTime . "'
                group by SUBSTRING_INDEX(cl.cdr_date_time,':',2),cl.serrc_code
                ORDER BY cl.serrc_code";
    return query($query);
}

// Load chart config.
/**
 * chart_name, duration_second, point_second
 */
$ChartConfigList = query("SELECT * FROM chart_config");
$chartConfigListChartName = TypeConvertorHelper::getKeyListFromArrayList($ChartConfigList, 'chart_name');


/**
 * get time config for charts.
 * 
 * @param string $chartName
 * 
 * @return stdClass {duration_second, point_second}
 */
function getTimeConfig($chartName){
    global $ChartConfigList, $chartConfigListChartName;
    $index = array_search($chartName, $chartConfigListChartName);
    if(!is_numeric($index)){
        echo "No chart name found at name: $chartName";
        exit;
    }
    $_res = new stdClass();
    $_res->duration_second = (int) $ChartConfigList[$index]['duration_second'];
    $_res->point_second = (int) $ChartConfigList[$index]['point_second'];
    return $_res;
}

/////////////////////////////// Setting /////////////////////////////////////
// Get now time stamp.
$endTimeStamp = strtotime(date('Y-m-d H:i:00'))-60;
// PULL BACK TIME 6 HOURS AGO.
//$endTimeStamp = $endTimeStamp - 3600*84;
// Cal 25 hours ago.
$startTimeStamp = $endTimeStamp - 90000;
// Convert to dateTime format.
$endDateTime = date('Y-m-d H:i', $endTimeStamp);
$endDateTime = $endDateTime . ":00";
$startDateTime = date('Y-m-d H:i', $startTimeStamp);
$startDateTime = $startDateTime . ":00";

// For all chart end-timestamp is now.
define('END_TIMESTAMP', strtotime(date('Y-m-d H:i:00'))-120);

/////////////////////////// Success Chart Info //////////////////////////////
// Get config for chart.
$successTimeConfig = getTimeConfig('success');
// Calc start and end date period for chart.
$successStartTimeStamp = END_TIMESTAMP - $successTimeConfig->duration_second;
$successStartDateTime = date('Y-m-d H:i:00', $successStartTimeStamp);
$successEndDateTime = date('Y-m-d H:i:00', END_TIMESTAMP);

$tehranGraph = successChart($successStartDateTime, $successEndDateTime, '1');
$tehranDataset = [];

$tabrizGraph = successChart($successStartDateTime, $successEndDateTime, '2');
$mashhadDataset = [];

$shirazGraph = successChart($successStartDateTime, $successEndDateTime, '3');
$shirazDataset = [];

$mashhadGraph = successChart($successStartDateTime, $successEndDateTime, '4');
$tabrizDataset = [];

// Make dataset
$x_axis_success = [];
for ($i = $successStartTimeStamp; $i <= END_TIMESTAMP; $i += $successTimeConfig->point_second) {
    $_startDateTime =  date('Y-m-d H:i', $i);
    $x_axis_success[] = $_startDateTime;
    // total success Dataset.
    $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($tehranGraph, ['dateTime', $_startDateTime]);
    if ($_index == -1) {
        $tehranDataset[] = 'NaN';
    } else {
        $tehranDataset[] = $tehranGraph[$_index]['errorCount'];
    }
    $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($tabrizGraph, ['dateTime', $_startDateTime]);
    if ($_index == -1) {
        $tabrizDataset[] = 'NaN';
    } else {
        $tabrizDataset[] = $tabrizGraph[$_index]['errorCount'];
    }
    $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($shirazGraph, ['dateTime', $_startDateTime]);
    if ($_index == -1) {
        $shirazDataset[] = 'NaN';
    } else {
        $shirazDataset[] = $shirazGraph[$_index]['errorCount'];
    }
    $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($mashhadGraph, ['dateTime', $_startDateTime]);
    if ($_index == -1) {
        $mashhadDataset[] = 'NaN';
    } else {
        $mashhadDataset[] = $mashhadGraph[$_index]['errorCount'];
    }
}

/////////////////////////// Failed Chart Info //////////////////////////////
// Get config for chart.
$failedTimeConfig = getTimeConfig('failed');
// Calc start and end date period for chart.
$failedStartTimeStamp = END_TIMESTAMP - $failedTimeConfig->duration_second;
$failedStartDateTime = date('Y-m-d H:i:00', $failedStartTimeStamp);
$failedEndDateTime = date('Y-m-d H:i:00', END_TIMESTAMP);

$tehranFailedGraph = failedChart($failedStartDateTime, $failedEndDateTime, '1');
$tehranFailedDataset = [];

$tabrizFailedGraph = failedChart($failedStartDateTime, $failedEndDateTime, '2');
$mashhadFailedDataset = [];

$shirazFailedGraph = failedChart($failedStartDateTime, $failedEndDateTime, '3');
$shirazFailedDataset = [];

$mashhadFailedGraph = failedChart($failedStartDateTime, $failedEndDateTime, '4');
$tabrizFailedDataset = [];

// Make dateset.
$x_axis_failed = [];
for ($i = $failedStartTimeStamp; $i <= END_TIMESTAMP; $i += $failedTimeConfig->point_second) {
    $_startDateTime =  date('Y-m-d H:i', $i);
    $x_axis_failed[] = $_startDateTime;

    //total failed dataset
    $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($tehranFailedGraph, ['dateTime', $_startDateTime]);
    if ($_index == -1) {
        $tehranFailedDataset[] = 'NaN';
    } else {
        $tehranFailedDataset[] = $tehranFailedGraph[$_index]['errorCount'];
    }
    $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($tabrizFailedGraph, ['dateTime', $_startDateTime]);
    if ($_index == -1) {
        $tabrizFailedDataset[] = 'NaN';
    } else {
        $tabrizFailedDataset[] = $tabrizFailedGraph[$_index]['errorCount'];
    }
    $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($shirazFailedGraph, ['dateTime', $_startDateTime]);
    if ($_index == -1) {
        $shirazFailedDataset[] = 'NaN';
    } else {
        $shirazFailedDataset[] = $shirazFailedGraph[$_index]['errorCount'];
    }
    $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($mashhadFailedGraph, ['dateTime', $_startDateTime]);
    if ($_index == -1) {
        $mashhadFailedDataset[] = 'NaN';
    } else {
        $mashhadFailedDataset[] = $mashhadFailedGraph[$_index]['errorCount'];
    }
}

/////////////////////////// Error Code Per City Info //////////////////////////////

/////////////////////////// Error Code Tehran Dataset //////////////////////////////
// Get config for chart.
$tehranErrorCodeTimeConfig = getTimeConfig('tehranErrorCode');
// Calc start and end date period for chart.
$tehranErrorCodeStartTimestamp = END_TIMESTAMP - $tehranErrorCodeTimeConfig->duration_second;
$tehranErrorCodeStartDateTime = date('Y-m-d H:i:00', $tehranErrorCodeStartTimestamp);
$tehranErrorCodeEndDateTime = date('Y-m-d H:i:00', END_TIMESTAMP);
$tehranErrorCodeCSV = errorCodeList($tehranErrorCodeStartDateTime, $tehranErrorCodeEndDateTime, '1');
$tehranErrorCodeData = errorCodeData($tehranErrorCodeStartDateTime, $tehranErrorCodeEndDateTime, "1", $tehranErrorCodeCSV);
$tehranErrorCodeDataList = TypeConvertorHelper::getKeyListFromArrayList($tehranErrorCodeData, 'errorCode');
$tehranCoordinates = TypeConvertorHelper::arrayToCoordinates($tehranErrorCodeDataList);
// Make data set.
$x_axis_tehran_error = [];
$x_axis_tehran_error_flag = true;
$tehranErrorDataSet = [];
foreach ($tehranCoordinates as $tehranCoordinate) {
    $errorCode = $tehranCoordinate[0];
    $_temp['errorCode'] = $errorCode;
    $tehranErrorData = array_slice($tehranErrorCodeData, $tehranCoordinate[1], $tehranCoordinate[2]);
    $_temp['dataset'] = [];
    for ($i = $tehranErrorCodeStartTimestamp; $i <= END_TIMESTAMP; $i += $tehranErrorCodeTimeConfig->point_second) {
        $_startDateTime =  date('Y-m-d H:i', $i);
        // To Calc x-axis.
        if($x_axis_tehran_error_flag){
            $x_axis_tehran_error[] = $_startDateTime;
        }
        $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($tehranErrorData, ['dateTime', $_startDateTime]);
        if ($_index == -1) {
            $_temp['dataset'][] = '0';
        } else {
            $_temp['dataset'][] = $tehranErrorData[$_index]['errorCount'];
        }
    }
    $tehranErrorDataSet[] = $_temp;
    // Unable flag.
    $x_axis_tehran_error_flag = false;
}


/////////////////////////// Error Code Tabriz Dataset //////////////////////////////
// Get config for chart.
$tabrizErrorCodeTimeConfig = getTimeConfig('tabrizErrorCode');
// Calc start and end date period for chart.
$tabrizErrorCodeStartTimestamp = END_TIMESTAMP - $tabrizErrorCodeTimeConfig->duration_second;
$tabrizErrorCodeStartDateTime = date('Y-m-d H:i:00', $tabrizErrorCodeStartTimestamp);
$tabrizErrorCodeEndDateTime = date('Y-m-d H:i:00', END_TIMESTAMP);
$tabrizErrorCodeCSV = errorCodeList($tabrizErrorCodeStartDateTime, $tabrizErrorCodeEndDateTime, '2');
$tabrizErrorCodeData = errorCodeData($tabrizErrorCodeStartDateTime, $tabrizErrorCodeEndDateTime, "2", $tabrizErrorCodeCSV);
$tabrizErrorCodeDataList = TypeConvertorHelper::getKeyListFromArrayList($tabrizErrorCodeData, 'errorCode');
$tabrizCoordinates = TypeConvertorHelper::arrayToCoordinates($tabrizErrorCodeDataList);

$x_axis_tabriz_error = [];
$x_axis_tabriz_error_flag = true;
$tabrizErrorDataSet = [];
foreach ($tabrizCoordinates as $tabrizCoordinate) {
    $errorCode = $tabrizCoordinate[0];
    $_temp['errorCode'] = $errorCode;
    $tabrizErrorData = array_slice($tabrizErrorCodeData, $tabrizCoordinate[1], $tabrizCoordinate[2]);
    $_temp['dataset'] = [];
    for ($i = $tabrizErrorCodeStartTimestamp; $i <= END_TIMESTAMP; $i += $tabrizErrorCodeTimeConfig->point_second) {
        $_startDateTime =  date('Y-m-d H:i', $i);
        // To Calc x-axis.
        if($x_axis_tabriz_error_flag){
            $x_axis_tabriz_error[] = $_startDateTime;
        }
        $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($tabrizErrorData, ['dateTime', $_startDateTime]);
        if ($_index == -1) {
            $_temp['dataset'][] = '0';
        } else {
            $_temp['dataset'][] = $tabrizErrorData[$_index]['errorCount'];
        }
    }
    $tabrizErrorDataSet[] = $_temp;
    // Unable flag.
    $x_axis_tabriz_error_flag = false;
}

/////////////////////////// Error Code Shiraz Dataset //////////////////////////////
// Get config for chart.
$shirazErrorCodeTimeConfig = getTimeConfig('shirazErrorCode');
// Calc start and end date period for chart.
$shirazErrorCodeStartTimestamp = END_TIMESTAMP - $shirazErrorCodeTimeConfig->duration_second;
$shirazErrorCodeStartDateTime = date('Y-m-d H:i:00', $shirazErrorCodeStartTimestamp);
$shirazErrorCodeEndDateTime = date('Y-m-d H:i:00', END_TIMESTAMP);
$shirazErrorCodeCSV = errorCodeList($shirazErrorCodeStartDateTime, $shirazErrorCodeEndDateTime, '3');
$shirazErrorCodeData = errorCodeData($shirazErrorCodeStartDateTime, $shirazErrorCodeEndDateTime, "3", $shirazErrorCodeCSV);
$shirazErrorCodeDataList = TypeConvertorHelper::getKeyListFromArrayList($shirazErrorCodeData, 'errorCode');
$shirazCoordinates = TypeConvertorHelper::arrayToCoordinates($shirazErrorCodeDataList);

$x_axis_shiraz_error = [];
$x_axis_shiraz_error_flag = true;
$shirazErrorDataSet = [];
foreach ($shirazCoordinates as $shirazCoordinate) {
    $errorCode = $shirazCoordinate[0];
    $_temp['errorCode'] = $errorCode;
    $shirazErrorData = array_slice($shirazErrorCodeData, $shirazCoordinate[1], $shirazCoordinate[2]);
    $_temp['dataset'] = [];
    for ($i = $shirazErrorCodeStartTimestamp; $i <= END_TIMESTAMP; $i += $shirazErrorCodeTimeConfig->point_second) {
        $_startDateTime =  date('Y-m-d H:i', $i);
        // To Calc x-axis.
        if($x_axis_shiraz_error_flag){
            $x_axis_shiraz_error[] = $_startDateTime;
        }
        $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($shirazErrorData, ['dateTime', $_startDateTime]);
        if ($_index == -1) {
            $_temp['dataset'][] = '0';
        } else {
            $_temp['dataset'][] = $shirazErrorData[$_index]['errorCount'];
        }
    }
    $shirazErrorDataSet[] = $_temp;
    // Unable flag.
    $x_axis_shiraz_error_flag = false;
}

/////////////////////////// Error Code Mashhad Dataset //////////////////////////////
// Get config for chart.
$mashhadErrorCodeTimeConfig = getTimeConfig('mashhadErrorCode');
// Calc start and end date period for chart.
$mashhadErrorCodeStartTimestamp = END_TIMESTAMP - $mashhadErrorCodeTimeConfig->duration_second;
$mashhadErrorCodeStartDateTime = date('Y-m-d H:i:00', $mashhadErrorCodeStartTimestamp);
$mashhadErrorCodeEndDateTime = date('Y-m-d H:i:00', END_TIMESTAMP);
$mashhadErrorCodeCSV = errorCodeList($mashhadErrorCodeStartDateTime, $mashhadErrorCodeEndDateTime, '4');
$mashhadErrorCodeData = errorCodeData($mashhadErrorCodeStartDateTime, $mashhadErrorCodeEndDateTime, "4", $mashhadErrorCodeCSV);
$mashhadErrorCodeDataList = TypeConvertorHelper::getKeyListFromArrayList($mashhadErrorCodeData, 'errorCode');
$mashhadCoordinates = TypeConvertorHelper::arrayToCoordinates($mashhadErrorCodeDataList);

$x_axis_mashhad_error = [];
$x_axis_mashhad_error_flag = true;
$mashhadErrorDataSet = [];
foreach ($mashhadCoordinates as $mashhadCoordinate) {
    $errorCode = $mashhadCoordinate[0];
    $_temp['errorCode'] = $errorCode;
    $mashhadErrorData = array_slice($mashhadErrorCodeData, $mashhadCoordinate[1], $mashhadCoordinate[2]);
    $_temp['dataset'] = [];
    for ($i = $mashhadErrorCodeStartTimestamp; $i <= END_TIMESTAMP; $i += $mashhadErrorCodeTimeConfig->point_second) {
        $_startDateTime =  date('Y-m-d H:i', $i);
        // To Calc x-axis.
        if($x_axis_mashhad_error_flag){
            $x_axis_mashhad_error[] = $_startDateTime;
        }
        $_index = TypeConvertorHelper::getIndexOfKeyValueInArray($mashhadErrorData, ['dateTime', $_startDateTime]);
        if ($_index == -1) {
            $_temp['dataset'][] = '0';
        } else {
            $_temp['dataset'][] = $mashhadErrorData[$_index]['errorCount'];
        }
    }
    $mashhadErrorDataSet[] = $_temp;
    // Unable flag.
    $x_axis_mashhad_error_flag = false;
}



/////////////////////////// Total Success DATA //////////////////////////////
$Total_Success_Code = [
    'x_axis' => $x_axis_success,
    'graph' => [
        [
            'label' => 'Tehran',
            'color' => [
                'background' => 'rgba(226, 24, 24, 0.5)',
                'border' => 'rgba(226, 24, 24, 1)'
            ],
            'dataset' => $tehranDataset
        ], [
            'label' => 'Tabriz',
            'color' => [
                'background' => 'rgba(70, 180, 45, 0.5)',
                'border' => 'rgba(70, 180, 45, 1)'
            ],
            'dataset' => $tabrizDataset
        ], [
            'label' => 'Shiraz',
            'color' => [
                'background' => 'rgba(58, 56, 199, 0.5)',
                'border' => 'rgba(58, 56, 199, 1)'
            ],
            'dataset' => $shirazDataset
        ], [
            'label' => 'Mashhad',
            'color' => [
                'background' => 'rgba(230, 151, 40, 0.5)',
                'border' => 'rgba(230, 151, 40, 1)'
            ],
            'dataset' => $mashhadDataset
        ]
    ]
];
/////////////////////////// Total Failed DATA //////////////////////////////
$Total_Failed_Code = [
    'x_axis' => $x_axis_failed,
    'graph' => [
        [
            'label' => 'Tehran',
            'color' => [
                'background' => 'rgba(226, 24, 24, 0.5)',
                'border' => 'rgba(226, 24, 24, 1)'
            ],
            'dataset' => $tehranFailedDataset
        ], [
            'label' => 'Tabriz',
            'color' => [
                'background' => 'rgba(70, 180, 45, 0.5)',
                'border' => 'rgba(70, 180, 45, 1)'
            ],
            'dataset' => $tabrizFailedDataset
        ], [
            'label' => 'Shiraz',
            'color' => [
                'background' => 'rgba(58, 56, 199, 0.5)',
                'border' => 'rgba(58, 56, 199, 1)'
            ],
            'dataset' => $shirazFailedDataset
        ], [
            'label' => 'Mashhad',
            'color' => [
                'background' => 'rgba(230, 151, 40, 0.5)',
                'border' => 'rgba(230, 151, 40, 1)'
            ],
            'dataset' => $mashhadFailedDataset
        ]
    ]
];

// Colors
$errorCodesColors = [
    0 => '255,0,0',
    99 => '255,64,0',
    100 => '255,128,0',
    1001 => '255,191,0',
    1002 => '255,255,0',
    1003 => '191,255,0',
    1004 => '128,255,0',
    1005 => '64,255,0',
    1006 => '0,255,0',
    1007 => '0,255,64',
    1008 => '0,255,128',
    1009 => '0,255,191',
    1010 => '0,255,255',
    2001 => '0,191,255',
    2002 => '0,128,255',
    2003 => '0,64,255',
    2004 => '0,0,255',
    2005 => '64,0,255',
    3001 => '128,0,255',
    3002 => '191,0,255',
    3003 => '255,0,255',
    3004 => '255,0,191',
    3005 => '255,0,128',
    3006 => '255,0,64',
    3201 => '255,128,64',
    3202 => '128,255,255'
];

// End colors


/////////////////////////// Total Tehran Error DATA //////////////////////////////
$Failed_Error_Code_Tehran = [
    'x_axis' => $x_axis_tehran_error,
    'graph' => []
];

foreach ($tehranErrorDataSet as $_dataset) {
    $colorCSV = $errorCodesColors[$_dataset['errorCode']];
    $_background = "rgba(".$colorCSV.", 0.5)";
    $_border  = "rgba(".$colorCSV.", 1)";
    $Failed_Error_Code_Tehran['graph'][] = [
        'label' => $_dataset['errorCode'],
        'color' => [
            'background' => $_background,
            'border' => $_border
        ],
        'dataset' => $_dataset['dataset']
    ];
}

/////////////////////////// Total Tabriz Error DATA //////////////////////////////
$Failed_Error_Code_Tabriz = [
    'x_axis' => $x_axis_tabriz_error,
    'graph' => []
];

foreach ($tabrizErrorDataSet as $_dataset) {
    $colorCSV = $errorCodesColors[$_dataset['errorCode']];
    $_background = "rgba(".$colorCSV.", 0.5)";
    $_border  = "rgba(".$colorCSV.", 1)";
    $Failed_Error_Code_Tabriz['graph'][] = [
        'label' => $_dataset['errorCode'],
        'color' => [
            'background' => $_background,
            'border' => $_border
        ],
        'dataset' => $_dataset['dataset']
    ];
}

/////////////////////////// Total Shiraz Error DATA //////////////////////////////
$Failed_Error_Code_Shiraz = [
    'x_axis' => $x_axis_shiraz_error,
    'graph' => []
];

foreach ($shirazErrorDataSet as $_dataset) {
    $colorCSV = $errorCodesColors[$_dataset['errorCode']];
    $_background = "rgba(".$colorCSV.", 0.5)";
    $_border  = "rgba(".$colorCSV.", 1)";
    $Failed_Error_Code_Shiraz['graph'][] = [
        'label' => $_dataset['errorCode'],
        'color' => [
            'background' => $_background,
            'border' => $_border
        ],
        'dataset' => $_dataset['dataset']
    ];
}

/////////////////////////// Total Mashhad Error DATA //////////////////////////////
$Failed_Error_Code_Mashhad = [
    'x_axis' => $x_axis_mashhad_error,
    'graph' => []
];

foreach ($mashhadErrorDataSet as $_dataset) {
    $colorCSV = $errorCodesColors[$_dataset['errorCode']];
    $_background = "rgba(".$colorCSV.", 0.5)";
    $_border  = "rgba(".$colorCSV.", 1)";
    $Failed_Error_Code_Mashhad['graph'][] = [
        'label' => $_dataset['errorCode'],
        'color' => [
            'background' => $_background,
            'border' => $_border
        ],
        'dataset' => $_dataset['dataset']
    ];
}

/////////////////////////// Prepare live Data //////////////////////////////
if(isset($_GET['ajax'])){
    header("content-type: application/json; charset=utf-8");
/*   
   // THIS SECTION MAKE FACKE DATA FOR LIVE CHART STREAM,
   // REMOVE IT FOR PRODUCTION
   // OR UNCOMMENT IT FOR TEST AND TO SEE HOW IT WORK.
    $c=0;
    foreach($Total_Success_Code['graph'] as $g){
        $j=0;
        foreach($Total_Success_Code['graph'][$c]['dataset'] as $d){
            $Total_Success_Code['graph'][$c]['dataset'][$j] = rand(100, 950);
            $j++;
        }
        $c++;
    }
*/    
    echo json_encode([
        'TotalSuccessCode' => $Total_Success_Code,
        'TotalFailedCode' => $Total_Failed_Code,
        'FailedErrorTehran' => $Failed_Error_Code_Tehran,
        'FailedErrorTabriz' => $Failed_Error_Code_Tabriz,
        'FailedErrorShiraz' => $Failed_Error_Code_Shiraz,
        'FailedErrorMashhad' => $Failed_Error_Code_Mashhad,
    ]); 
}
