<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

/**
 * Set timezone for app in tehran.
 */
date_default_timezone_set("Asia/Tehran");

$timestamp = strtotime("yesterday");
$yesterday = date("Y-m-d",$timestamp);

//Iran daily per area code 
$sql = "set statement_timeout= '120000s';
    commit;
    insert into cbs_cbp_data_cell_log_daily (
        select date_trunc('day',cdr_date_time),city_code,rat_type,service_usage_type,rg,ugw_address,sum(actual_usage),sum(debit_amount),sum(free_amount)
        from cbs_cbp_data_cell_log
        where date_trunc('day',cdr_date_time) = '$yesterday' 
        group by 1,2,3,4,5,6);";

$start = microtime(true);    
$queryStatus = query($sql)->status;
    if(!$queryStatus){
        file_put_contents("/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/data/daily_inserter.log", "$queryStatus\n Error in query!\n", FILE_APPEND | LOCK_EX);
    }   
    
$now = date("Y-m-d",time());
$finish = ((microtime(true)-$start)*1000000)/60;

echo "$now Iran_Data_Daily_Time: $finish";
file_put_contents("/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/data/daily_inserter.log", "finish(min): $finish\n\n", FILE_APPEND | LOCK_EX);
