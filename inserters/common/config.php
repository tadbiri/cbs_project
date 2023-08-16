<?php
/**
* I know that for last day of year a bug may be happen in make file cache 
* feature, so it's will be fixed in a day.
*
* Another thing is that we must a double check in graph.js to test
* NaN state and Zero in chart view.
*/

/**
 * Set timezone for app in tehran.
 */
date_default_timezone_set("Asia/Tehran");

/**
 * Directory name thap whole app inside in.
 */
define('APP_DIR_NAME', 'cbs');

/**
 * To implement time shift in app use TIMESTAMP_SHIFT.
 * In production or use real time in app it's must be zero.
 * For all chart end-timestamp is now.
 */
//define('TIMESTAMP_SHIFT', 24*3600*150-3600);
define('TIMESTAMP_SHIFT', 0);

/**
 * Calculate end timestamp.
 */
function get_end_timestamp(){
    $end_timestamp = strtotime(date('Y-m-d H:i:00'))-TIMESTAMP_SHIFT;
    return $end_timestamp;
}

/**
 * Path of directory that hold cache files.
 */
define('CACHE_POOL_DIR', dirname(__DIR__, 1)."/".APP_DIR_NAME."/cache-pool/");


/**
 * Break files to chunks to implement concurrent processing
 * In cbs-log-reader.
 */
//define('CONCURRENT_LOG_FILE_COUNT_TO_PROCESS', 1);


/**
 * In here control print log in inserter script. 
 */
define('LOG_IN_INSERTER', true);

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
define('CACHE_FILE_TYPE', 'Dev');

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
 * Define config for api address. 
 */
define('API_PURE_URL', "/".APP_DIR_NAME."/app/json.php");
define('API_ANALYTIC_PURE_URL', "/".APP_DIR_NAME."/app/json-analytic.php");

/**
 * define database connector
 * define type converter helper
 */
require_once __DIR__."/type_convertor.php";
require_once __DIR__."/database1.php";