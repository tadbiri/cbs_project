<?php



/**
 * define application root path
 */

define('APP_DIR_NAME', 'icdr');

$currentDIR = dirname(__DIR__);
$currentDIRArray = explode("/", $currentDIR);
$cutPath = [];
for($i=1; $i< count($currentDIRArray) ;$i++){
        $cutPath[] = $currentDIRArray[$i];
        if ($currentDIRArray[$i]== APP_DIR_NAME){
          break;
        };
};
$FullAppPath = '';
for($i=1 ; $i< count($currentDIRArray) ;$i++){
        $FullAppPath .= "/".$currentDIRArray[$i];
        if ($currentDIRArray[$i]== APP_DIR_NAME){
                break;
              };
};
$URLDIRPath = '';
for($i=count($cutPath) ; $i< count($currentDIRArray) ;$i++){
        $URLDIRPath .= "/".$currentDIRArray[$i];
};


define('FullChartPath', $currentDIR);
define('URLChartPath', $URLDIRPath);
define('URLAppPath', "/".APP_DIR_NAME);
define('FullCommonPath', $FullAppPath."/common");

//echo "FullChartPath: ".FullChartPath."\n";
//echo "URLChartPath: ".URLChartPath."\n";
//echo "URLAppPath: ".URLAppPath."\n";
//echo "FullCommonPath: ".FullCommonPath."\n";

/**
 * Path of directory that hold cache files.
 */
define('CACHE_POOL_DIR', FullChartPath."/cachepool/");

/**
 * Define config for api address. 
 */
define('API_PURE_URL', URLChartPath."/common/libs/json.php");

/**
 * To implement time shift in app use TIMESTAMP_SHIFT.
 * In production or use real time in app it's must be zero.
 * For all chart end-timestamp is now.
 */
//define('TIMESTAMP_SHIFT', 24*3600*57-3600);
define('TIMESTAMP_SHIFT', 0);

/**
 * Set timezone for app in tehran.
 */
date_default_timezone_set("Asia/Tehran");

/**
 * Calculate end timestamp.
 */
function get_end_timestamp(){
    $end_timestamp = strtotime(date('Y-m-d H:i:00'))-TIMESTAMP_SHIFT;
    return $end_timestamp;
}

//echo "endTimestamp is now: ".date('Y-m-d H:i:s', get_end_timestamp())."\n";

/**
 * Show errors that happen in development.
 * For production area comment this section.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);

/**
 * Configure for cache feature.
 * Eeach Line of file struct described here.
 * 
 * Bellow example for development area. 
 * Example a full line:
 * 1647383400, 2022-03-16 02:00:00, 1647438540,       2022-03-16 17:19:00, Tehran:11992,Tabriz:6874,Shiraz:7564,Mashhad:6366
 * fileIndex , fileIndexDatetime,   fetchTimestamp,   fetchDatetime      , Entity CSV -> k1:v1, k2:v2, ...     
 * 
 * Example a empty line.
 * 1647439800,2022-03-16 17:40:00,
 * fileIndex, fileIndexDatetime
 * 
 * Bellow example for production area.
 * Example a full line:
 * 1647383400, 1647438540,     Tehran:11992,Tabriz:6874,Shiraz:7564,Mashhad:6366
 * fileIndex , fetchTimestamp, Entity CSV -> k1:v1, k2:v2, ...     
 * 
 * Example a empty line.
 * 1647439800,
 * fileIndex,
 
 */


/**
 * It's can be 'Prod' or 'Dev',
 * When change it consider remove whole cache.
 */
define('CACHE_FILE_TYPE', 'Prod');

if(CACHE_FILE_TYPE == 'Dev'){
    define('EMPTY_LINE_COMMA_COUNT', 2);
    define('FILEINDEX_TIMESTAMP_INDEX', 0);
    define('FETCH_TIMESTAMP_INDEX', 2);
    define('ENTITY_START_INDEX', 4);
    define('ENTITY_KEY_IS_EXIST', true);
}elseif(CACHE_FILE_TYPE == 'Prod'){
    define('EMPTY_LINE_COMMA_COUNT', 1);
    define('FILEINDEX_TIMESTAMP_INDEX', 0);
    define('FETCH_TIMESTAMP_INDEX', 1);
    define('ENTITY_START_INDEX', 2);
    define('ENTITY_KEY_IS_EXIST', true);
}else{
    echo "Can not run app, Wrong config in CACHE_FILE_TYPE \n";
    exit;
}

/**
 * Load some libs that require in app.
 */
require_once FullCommonPath."/db/database_new.php";

require_once FullCommonPath."/db/type_convertor_new.php";

require_once FullCommonPath."/amchart/chart/config.php";

define('API_ANALYTIC_PURE_URL',  URLChartPath."/common/libs/jsonanalytic.php");