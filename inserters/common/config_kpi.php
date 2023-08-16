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
define('APP_DIR_NAME', 'kpi');


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
 * define database connector
 * define type converter helper
 */
require_once __DIR__."/type_convertor.php";
require_once __DIR__."/database_kpi.php";