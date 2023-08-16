select t1.time,t1.province_name,t1.live/t2.his from (   
    SELECT date_trunc('hour',a.date_time) as "time",p.province_name,cast(max(a.cell_count) as float) as "live"
    FROM cbs_cell_availability_province a
    left join province_loc p on a.province_code=p.province_code
    where p.province_name <> '1'
    and p.province_name = 'Sistan'
    group by 1,2
    order by 1 asc
) as t1
union all (
    SELECT date_trunc('hour',a.date_time) + interval '7 day' as "time",p.province_name,cast(max(a.cell_count) as float) as "his"
    FROM cbs_cell_availability_province a
    left join province_loc p on a.province_code=p.province_code
    where p.province_name <> '1'
    and p.province_name = 'Sistan'
    and a.date_time < now() - interval '7 day'
    group by 1,2
    order by 1 
) as t2 


