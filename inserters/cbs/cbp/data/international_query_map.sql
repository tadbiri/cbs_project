select x.key,x.country,x.country_digit,x."Base",x."Current",x."Deviation",x."Deviation%" from (
select aa.key,bb.country,bb.country_digit,bb."Service Usage1" as "Base",aa."Service Usage" as "Current",(aa."Service Usage" - bb."Service Usage1") as "Deviation",((aa."Service Usage" - bb."Service Usage1")/bb."Service Usage1") as "Deviation%" from (
(SELECT 1 as "key",b.country,b.country_digit,(sum(a.call_min)/60) as "Service Usage"
FROM cbs_cbp_rec_cell_log_daily a
left join country_code b on b.country_code = substring(a.area_code,2)
where cdr_date_time > date(now()) - interval '2 day'
and area_code like 'r%' and area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
group by 2,3
order by 4 desc) as aa
left join
(SELECT 1 as "key",b.country,b.country_digit,(sum(a.call_min)/60) as "Service Usage1"
FROM cbs_cbp_rec_cell_log_daily a
left join country_code b on b.country_code = substring(a.area_code,2)
where date_trunc('day',cdr_date_time) = '$Base_Date'
and area_code like 'r%' and area_code not in ('r4','r8','r3','r80','r2','r5','r9','r107')
group by 2,3
order by 4 desc) as bb on aa.country_digit = bb.country_digit)
union all(
select aa.key,bb.country,bb.country_digit,bb."Service Usage1" as "Base",aa."Service Usage" as "Current",(aa."Service Usage" - bb."Service Usage1") as "Deviation",((aa."Service Usage" - bb."Service Usage1")/bb."Service Usage1") as "Deviation%" from (
(SELECT 2 as "key",b.country,b.country_digit,(sum(a.actual_usage)/(10^6)) as "Service Usage"
FROM cbs_cbp_data_cell_log_daily a
left join country_code b on b.country_code = substring(a.city_code,2)
where cdr_date_time > date(now()) - interval '2 day'
and city_code like 'r%' and city_code not in ('r4','r8','r3','r80','r2','r5','r9','r107','r-1','r9999')
and service_usage_type = '33'
group by 2,3
order by 4 desc) as aa
left join
(SELECT 2 as "key",b.country,b.country_digit,(sum(a.actual_usage)/(10^6)) as "Service Usage1"
FROM cbs_cbp_data_cell_log_daily a
left join country_code b on b.country_code = substring(a.city_code,2)
where date_trunc('day',cdr_date_time) = '$Base_Date'
and city_code like 'r%' and city_code not in ('r4','r8','r3','r80','r2','r5','r9','r107','r-1','r9999')
and service_usage_type = '33'
and a.actual_usage <> '0'
group by 2,3
order by 4 desc) as bb on aa.country_digit = bb.country_digit))
union all
(select aa.key,bb.country,bb.country_digit,bb."Service Usage1" as "Base",aa."Service Usage" as "Current",(aa."Service Usage" - bb."Service Usage1") as "Deviation",((aa."Service Usage" - bb."Service Usage1")/bb."Service Usage1") as "Deviation%" from (
(SELECT 3 as "key",b.country,b.country_digit,cast((sum(a.e_count)) as float) as "Service Usage"
FROM cbs_cbp_sms_cell_log_daily a
left join country_code b on b.country_code = substring(a.city_code,2)
where cdr_date_time > date(now()) - interval '2 day'
and city_code like 'r%' 
and service_usage_type = '5'
group by 2,3
order by 4 desc)as aa
left join
(SELECT 3 as "key",b.country,b.country_digit,cast((sum(a.e_count)) as float) as "Service Usage1"
FROM cbs_cbp_sms_cell_log_daily a
left join country_code b on b.country_code = substring(a.city_code,2)
where date_trunc('day',cdr_date_time) = '$Base_Date'
and city_code like 'r%' 
and service_usage_type = '5'
group by 2,3
order by 4 desc) as bb on aa.country_digit = bb.country_digit))

) x where x.key = '$Service'
and country <> 'null'
