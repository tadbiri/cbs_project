select (a.current-b.base)/cast(b.base as float) as "Total_Service_Deviation%" from(
    select '1' as "key",count(distinct(a1.area_code)) as "current" from (
    select distinct(area_code) from cbs_cbp_rec_cell_log_daily
    where date_trunc('day',cdr_date_time) = '$Current_Date'
    and area_code like 'r%' and area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
    union all(
    select distinct(city_code) from cbs_cbp_data_cell_log_daily
    where date_trunc('day',cdr_date_time) = '$Current_Date'
    and city_code like 'r%' and city_code not in ('r4','r8','r3','r80','r2','r5','r9','r107','r-1','r9999')
    and service_usage_type = '33')
    union all (
    select distinct(city_code) from cbs_cbp_sms_cell_log_daily
    where date_trunc('day',cdr_date_time) = '$Current_Date'
    and city_code like 'r%' 
    and service_usage_type = '5') ) a1) a
left join 
(select '1' as "key",count(distinct(b1.area_code)) as "base" from (
select distinct(area_code) from cbs_cbp_rec_cell_log_daily
where date_trunc('day',cdr_date_time) = '$Base_Date'
and area_code like 'r%' and area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
union all(
select distinct(city_code) from cbs_cbp_data_cell_log_daily
where date_trunc('day',cdr_date_time) = '$Base_Date'
and city_code like 'r%' and city_code not in ('r4','r8','r3','r80','r2','r5','r9','r107','r-1','r9999')
and service_usage_type = '33')
union all (
select distinct(city_code) from cbs_cbp_sms_cell_log_daily
where date_trunc('day',cdr_date_time) = '$Base_Date'
and city_code like 'r%' 
and service_usage_type = '5') ) b1 ) b on a.key=b.key

select (a.current-b.base)/cast(b.base as float) as "Voice_Deviation%" from(
select '1' as "key",count(a1.area_code) as "current" from (
select distinct(area_code) from cbs_cbp_rec_cell_log_daily
where date_trunc('day',cdr_date_time) = '$Current_Date'
and area_code like 'r%' and area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
) a1 ) a
left join (
    select '1' as "key",count(b1.area_code) as "base" from (
    select distinct(area_code) from cbs_cbp_rec_cell_log_daily
    where date_trunc('day',cdr_date_time) = '$Base_Date'
    and area_code like 'r%' and area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
) b1 ) b on a.key=b.key


select (a.current-b.base)/cast(b.base as float) as "Data_Deviation%" from(
select '1' as "key",count(a1.city_code) as "current" from (
select distinct(city_code) from cbs_cbp_data_cell_log_daily
where date_trunc('day',cdr_date_time) = '$Current_Date'
and city_code like 'r%' and city_code not in ('r4','r8','r3','r80','r2','r5','r9','r107','r-1','r9999')
and service_usage_type = '33'
) a1 ) a
left join (
    select '1' as "key",count(b1.city_code) as "base" from (
    select distinct(city_code) from cbs_cbp_data_cell_log_daily
where date_trunc('day',cdr_date_time) = '$Base_Date'
and city_code like 'r%' and city_code not in ('r4','r8','r3','r80','r2','r5','r9','r107','r-1','r9999')
and service_usage_type = '33'
) b1 ) b on a.key=b.key


select (a.current-b.base)/cast(b.base as float) as "Data_Deviation%" from(
select '1' as "key",count(a1.city_code) as "current" from (
select distinct(city_code) from cbs_cbp_sms_cell_log_daily
where date_trunc('day',cdr_date_time) = '$Current_Date'
and city_code like 'r%' 
and service_usage_type = '5'
) a1 ) a
left join (
    select '1' as "key",count(b1.city_code) as "base" from (
    select distinct(city_code) from cbs_cbp_sms_cell_log_daily
where date_trunc('day',cdr_date_time) = '$Base_Date'
and city_code like 'r%' 
and service_usage_type = '5'
) b1 ) b on a.key=b.key

select distinct(city_code) from cbs_cbp_sms_cell_log_daily
where date_trunc('day',cdr_date_time) = '$Current_Date'
and city_code like 'r%' 
and service_usage_type = '5'

select a.cdr_date_time,a.current,b.base from (
    select '1' as "key",cdr_date_time,count(distinct(area_code)) as "current" from cbs_cbp_rec_cell_log_daily
    where area_code like 'r%' and area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
    group by 1,2) a
left join (
    select '1' as "key",cdr_date_time,count(distinct(area_code)) as "base" from cbs_cbp_rec_cell_log_daily
    where date_trunc('day',cdr_date_time) = '$Base_Date' 
    and area_code like 'r%' and area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
    group by 1,2
) b on a.key=b.key


select x.cdr_date_time as "time",(x.current-y.base)/cast(y.base as float) as "Deviation%" from (
    select '1' as "key",cdr_date_time,count(code) as "current" from (
        select cdr_date_time,code as "code" from (
            select a.cdr_date_time,a.area_code as "code" from cbs_cbp_rec_cell_log_daily a
            where a.area_code like 'r%' and a.area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
            union all (
            select cdr_date_time,city_code as "code" from cbs_cbp_data_cell_log_daily
            where city_code like 'r%' and city_code not in ('r4','r8','r3','r80','r2','r5','r9','r107','r-1','r9999')
            and service_usage_type = '33')
            union all (
            select cdr_date_time,city_code as "code" from cbs_cbp_sms_cell_log_daily
            where date_trunc('day',cdr_date_time) = '$Current_Date'
            and city_code like 'r%' 
            and service_usage_type = '5')
        ) a
        group by 1,2
        order by 1 desc
    ) b
    group by 1,2
) x
left join (
    select '1' as "key",cdr_date_time,count(code) as "base" from (
        select cdr_date_time,code as "code" from (
            select a.cdr_date_time,a.area_code as "code" from cbs_cbp_rec_cell_log_daily a
            where a.area_code like 'r%' and a.area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
            union all (
            select cdr_date_time,city_code as "code" from cbs_cbp_data_cell_log_daily
            where city_code like 'r%' and city_code not in ('r4','r8','r3','r80','r2','r5','r9','r107','r-1','r9999')
            and service_usage_type = '33')
            union all (
            select cdr_date_time,city_code as "code" from cbs_cbp_sms_cell_log_daily
            where date_trunc('day',cdr_date_time) = '$Current_Date'
            and city_code like 'r%' 
            and service_usage_type = '5')
        ) a
        group by 1,2
        order by 1 desc
    ) b
    where cdr_date_time = '2023-03-03'
    group by 1,2
) y on x.key=y.key
where x.cdr_date_time > '2023-03-03'
order by 1 asc



select x.cdr_date_time as "time",x.current as "Voice" from (
    select cdr_date_time,count(code) as "current" from (
        select cdr_date_time,code as "code" from (
            select a.cdr_date_time,a.area_code as "code" from cbs_cbp_rec_cell_log_daily a
            where a.area_code like 'r%' and a.area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
        ) a
        group by 1,2
    ) b
    group by 1
) x
where x.cdr_date_time = '$Current_Date'
