select x.key,x.province_name,x.lat,x.long,x."Base",x."Current",x."Deviation",x."Deviation%" from (

(select 1 as "key",a.province_name,a.lat,a.long,b.w as "Base",a.live as "Current",(a.live-b.w) as "Deviation",(a.live-b.w)/b.w as "Deviation%" from 
(SELECT p.province_name,cast(p.lat as float),cast(p.long as float),(sum(call_min)/60)/(10^6) as "live" 
FROM cbs_cbp_rec_cell_log_daily a
left join province_loc p on p.province_code=a.area_code
where cdr_date_time > date(now()) - interval '2 day'
group by 1,2,3) a
left join (SELECT p.province_name,cast(p.lat as float),cast(p.long as float),(sum(call_min)/60)/(10^6) as "w" 
FROM cbs_cbp_rec_cell_log_daily a
left join province_loc p on p.province_code=a.area_code
where date_trunc('day',cdr_date_time) = '$Base_Date'
group by 1,2,3) b on a.province_name=b.province_name)

union all

(select 2 as "key",a.province_name,a.lat,a.long,b.w as "Base",a.live as "Current",(a.live-b.w) as "Deviation",(a.live-b.w)/b.w as "Deviation%" from 
(SELECT p.province_name,cast(p.lat as float),cast(p.long as float),(sum(actual_usage)/(10^12)) as "live" 
FROM cbs_cbp_data_cell_log_daily a
left join province_loc p on p.province_code=a.city_code
where cdr_date_time > date(now()) - interval '2 day'
group by 1,2,3) a
left join (SELECT p.province_name,cast(p.lat as float),cast(p.long as float),(sum(actual_usage)/(10^12)) as "w" 
FROM cbs_cbp_data_cell_log_daily a
left join province_loc p on p.province_code=a.city_code
where date_trunc('day',cdr_date_time) = '$Base_Date'
group by 1,2,3) b on a.province_name=b.province_name)

union all

(select 3 as "key",a.province_name,a.lat,a.long,b.w as "Base",a.live as "Current",(a.live-b.w) as "Deviation",(a.live-b.w)/b.w as "Deviation%" from  
(SELECT p.province_name,cast(p.lat as float),cast(p.long as float),cast(sum(e_count)/(10^6) as float) as "live" 
FROM cbs_cbp_sms_cell_log_daily a
left join province_loc p on p.province_code=a.city_code
where cdr_date_time > date(now()) - interval '2 day'
group by 1,2,3) a
left join (SELECT p.province_name,cast(p.lat as float),cast(p.long as float),cast(sum(e_count)/(10^6) as float)as "w" 
FROM cbs_cbp_sms_cell_log_daily a
left join province_loc p on p.province_code=a.city_code
where date_trunc('day',cdr_date_time) = '$Base_Date'
group by 1,2,3) b on a.province_name=b.province_name)

) x where x.key = $Service
and province_name <> 'null'
order by 5 desc
