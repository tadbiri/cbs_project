<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";

/**
 * Set timezone for app in tehran.
 */


while(true){

date_default_timezone_set("Asia/Tehran");
$timestamp = strtotime("yesterday");
$yesterday = date("Y-m-d",$timestamp);
$now = date("Y-m-d h:i:00");

//Iran daily per area code 
$sql10m =   "set statement_timeout= '120s';
            insert into cbs_cell_availability_province (
                select date_trunc('minute',now()) as \"date_time\",'10mv' as \"interval\",a.area_code as \"province_code\",count(a.cell_code) as \"cell_count\" from (
                    SELECT cell_code,area_code FROM public.cbs_cbp_rec_cell_log 
                    where area_code not like 'r%' and cdr_date_time > now() - interval '10 minutes'
                    group by 1,2
                    order by 1,2 asc) a
            where char_length(a.area_code) = '2' 
            group by 1,2,3
            order by 1 asc,2,3);";

$start1 = microtime(true);    
$queryStatus = query($sql10m)->status;  
    
//$now = date("Y-m-d",time());
$duration = (microtime(true)-$start1);
$hours = (int)((microtime(true)-$start1)/60/60);
$minutes = (int)($duration/60)-$hours*60;
$seconds = (int)$duration-$hours*60*60-$minutes*60;


echo "$now cell_ava_10m: $minutes:$seconds \n";

sleep(600);
}