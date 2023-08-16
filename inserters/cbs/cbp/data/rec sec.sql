select t1.time,t1.province,(t2.avgi-t1.avgt)/t1.avgt as "AVG Deviation" 
from (
    select x.time as "time",x.province,x.tusage/y.tnumber as "avgt" 
    from ( 
        SELECT date_trunc('day',a.cdr_date_time) as "time",b.province_name as "province",sum(a.call_min) as "tusage" 
        FROM cbs_cbp_rec_cell_log_daily a
        left join cbs_cell_map b on cast(a.area_code as bigint)=b.city_code
        where a.area_code not like 'r%'
        and a.area_code not like '98-%'
        and a.area_code not like '0%'
        and b.province_name not like '1'
        group by 1,2
        ORDER BY 1 asc
    ) x left join
    (
        SELECT date_trunc('day',a.cdr_date_time) as "time",b.province_name as "province",sum(a.e_count) as "tnumber"
        FROM cbs_cbp_rec_sec_log_daily a
        left join cbs_cell_map b on cast(a.area_code as bigint)=b.city_code
        where a.area_code not like 'r%'
        and b.province_name not like ' '
        and b.province_name not like 'TS'
        group by 1,2
        ORDER BY 1 asc
    ) y on x.time=y.time and x.province=y.province
) t1 left join
(
    select x.time as "time",x.province,x.iusage/y.inumber as "avgi"
    from ( 
        SELECT date_trunc('day',a.cdr_date_time) as "time",b.province_name as "province",sum(a.call_min) as "iusage" 
        FROM cbs_cbp_rec_cell_log_daily a
        left join cbs_cell_map b on cast(a.area_code as bigint)=b.city_code
        where a.area_code not like 'r%'
        and a.area_code not like '98-%'
        and a.area_code not like '0%'
        and b.province_name not like '1'
        and a.rec_type = 'ims'
        group by 1,2
        ORDER BY 1 asc
    ) x left join
    (
        SELECT date_trunc('day',a.cdr_date_time) as "time",b.province_name as "province",sum(a.e_count) as "inumber"
        FROM cbs_cbp_ims_sec_log_daily a
        left join cbs_cell_map b on cast(a.area_code as bigint)=b.city_code
        where a.area_code not like 'r%'
        and b.province_name not like ' '
        and b.province_name not like 'TS'
        group by 1,2
        ORDER BY 1 asc
    ) y on x.time=y.time and x.province=y.province
) t2 on t1.time=t2.time and t1.province=t2.province
order by 1 asc