<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

/**
 * Set timezone for app in tehran.
 */
date_default_timezone_set("Asia/Tehran");

$now = date("Y-m-d",time());
echo "$now\n";

//Iran daily per area code 
$sql = "set statement_timeout= '120000s';
    commit;
    insert into cbs_cbp_data_cell_log_daily (cdr_date_time,city_code,rat_type,service_usage_type,rg,ugw_address,actual_usage,debit_amount,free_amount)
        select date_trunc('day',cdr_date_time),city_code,rat_type,service_usage_type,rg,ugw_address,sum(actual_usage),sum(debit_amount),sum(free_amount)
        from cbs_cbp_data_cell_log
        where date_trunc('day',cdr_date_time) = '$now' 
        group by 1,2,3,4,5,6
        ON CONFLICT ON CONSTRAINT cbs_cbp_data_cell_log_daily_uniq_cons DO UPDATE
        SET actual_usage=excluded.actual_usage, debit_amount=excluded.debit_amount, free_amount=excluded.free_amount;";

$start = microtime(true);    
$queryStatus = query($sql)->status;
    if(!$queryStatus){
        file_put_contents("daily_inserter.log", "$queryStatus\n Error in query!\n", FILE_APPEND | LOCK_EX);
    }    
$finish = (microtime(true)-$start);
$finish = number_format($finish,2,'.','');
echo "Iran_Daily_Time: $finish\n\n";

file_put_contents("daily_inserter.log", "$finish\n\n", FILE_APPEND | LOCK_EX);

/*
$cbs_cell_info = query("SELECT * FROM cbs_cell_info")->result;


function newCell ($lac_id, $cell_code, $cbs_cell_code){
    $query = "INSERT INTO cbs_cell_info (lac_id, cell_code,cbs_cell_code) VALUES ($lac_id, $cell_code, $cbs_cell_code)";
    return query($query);
}


IMS

insert into cbs_cbp_rec_cell_log_iran (
select date_trunc('day',cdr_date_time),service_type,usage_service_type,sum(call_min),sum(call_debit_amount),sum(call_free_amount),sum(call_min_free),'8888' from cbs_cbp_rec_cell_log
where date_trunc('day',cdr_date_time) = '2023-01-02' and area_code= '8888'
group by 1,2,3)


insert into cbs_cbp_rec_cell_log_iran (
select date_trunc('day',cdr_date_time),service_type,usage_service_type,sum(call_min),sum(call_debit_amount),sum(call_free_amount),sum(call_min_free)  from cbs_cbp_rec_cell_log
where date_trunc('day',cdr_date_time) = '2023-01-02'
group by 1,2,3)
*/