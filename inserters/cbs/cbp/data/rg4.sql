select p.province_name as "Province_name",t2.aub as "Base Date",t1.au1 as "$Current_Date",(t1.au1-t2.aub) as "Deviation",(t1.au1-t2.aub)/t2.aub as "Deviation%"
from 
    (
    select date_trunc('day',cdr_date_time),city_code,sum(actual_usage) as "au1" from cbs_cbp_data_cell_log_daily 
    where rg = '4'
    and date_trunc('day',cdr_date_time) = '$Current_Date'
    group by 1,2
    ) as "t1"
left join 
    (select date_trunc('day',cdr_date_time) as "time",city_code,sum(actual_usage) as "aub" from cbs_cbp_data_cell_log_daily 
    where rg = '4'
    AND date_trunc('day',cdr_date_time) = '$Base_Date'
    group by 1,2) as "t2" on t1.city_code = t2.city_code
left join province p on t1.city_code=p.province_code
where p.province_name <> 'null'
and p.province_name <> 'TS'
order by 2 desc