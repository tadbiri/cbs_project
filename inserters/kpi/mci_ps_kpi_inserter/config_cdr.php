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
 * define database connector
 * define type converter helper
 */
require_once "type_convertor.php";
require_once "database_cdr.php";