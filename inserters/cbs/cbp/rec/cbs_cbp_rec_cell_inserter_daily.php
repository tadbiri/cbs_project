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
insert into cbs_cbp_rec_cell_log_daily (
    select date_trunc('day',cdr_date_time),area_code,service_type,usage_service_type,sum(call_min),sum(call_debit_amount),sum(call_free_amount),sum(call_min_free)
    from cbs_cbp_rec_cell_log
    where date_trunc('day',cdr_date_time) = '$yesterday'
    and cell_code not like '%i%'
    group by 1,2,3,4);";

$sql1 = "set statement_timeout= '120000s';
commit;
insert into cbs_cbp_rec_cell_log_daily (
    select date_trunc('day',cdr_date_time),area_code,service_type,usage_service_type,sum(call_min),sum(call_debit_amount),sum(call_free_amount),sum(call_min_free),'ims' as \"rec_type\"
    from cbs_cbp_rec_cell_log
    where date_trunc('day',cdr_date_time) = '$yesterday'
    and cell_code like '%i%'
    group by 1,2,3,4);";

$start = microtime(true);
$queryStatus = query($sql)->status;
    if(!$queryStatus){
        file_put_contents("/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/rec/daily_inserter.log", "$queryStatus\n Error in query!\n", FILE_APPEND | LOCK_EX);
    }
    $now = date("Y-m-d",time());
    $finish = ((microtime(true)-$start)*1000000)/60;
    
    echo "$now Iran_Voice_Daily_Time: $finish";
    file_put_contents("/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/rec/daily_inserter.log", "finish(min): $finish\n\n", FILE_APPEND | LOCK_EX);


$start = microtime(true);
$queryStatus = query($sql1)->status;
    if(!$queryStatus){
        file_put_contents("/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/rec/daily_inserter.log", "$queryStatus\n Error in query!\n", FILE_APPEND | LOCK_EX);
    }
    $now = date("Y-m-d",time());
    $finish = ((microtime(true)-$start)*1000000)/60;

    echo "$now Iran_IMS_Daily_Time: $finish";
    file_put_contents("/cbshome/cdr_analysis/app/cdr_db_inserter/cbs/cbp/rec/daily_inserter.log", "finish(min): $finish\n\n", FILE_APPEND | LOCK_EX);
