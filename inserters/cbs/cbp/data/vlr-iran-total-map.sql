select z1."Province",z1."lat",z1."long",z1."Base",z1."Current",z1."Deviation",z1."Deviation%" from (
    select t1.time,t1.province_name as "Province",t1.lat as "lat",t1.long as "long",t2.sub as "Base",t1.sub as "Current",t1.sub-t2.sub as "Deviation",cast((t1.sub-t2.sub) as float)/NULLIF(t2.sub,0) as "Deviation%" 
    from (
        select b.time,p.province_name,l.lat as "lat",l.long as "long",sum(c.subscribers_total) as "sub" 
        from (
            select distinct date_trunc('day',a.date) as "day",max(a.date) OVER (PARTITION BY date_trunc('day',a.date)) as "time" 
            from nokia_vlr a
            where date_trunc('day',date) > '2023-05-29'
            order by 1 desc,2 desc
            limit 1
        ) b 
        left join nokia_vlr c on b.time=c.date 
        left join nokia_province p on c.node_name=p.node_name
        left join province_loc l on p.node_name=l.msc_code
        where p.province_name <> 'Qom'
        group by 1,2,3,4
        order by 1 desc,2 asc
    ) t1 
    left join (
        select b.time,p.province_name,sum(c.subscribers_total) as "sub" 
        from (
            select distinct date_trunc('day',a.date) as "day",max(a.date) OVER (PARTITION BY date_trunc('day',a.date)) as "time"
            from nokia_vlr a
            where date_trunc('day',date) > '2023-05-29'
            order by 1 desc,2 desc
        ) b 
        left join nokia_vlr c on b.time=c.date 
        left join nokia_province p on c.node_name=p.node_name
        where date_trunc('day',b.time) = '$Base_Date'
        group by 1,2
        order by 1 desc,2 asc
    ) t2 on t1.province_name=t2.province_name

    union all (

    select t1.time,t1.province_name as "Province",t1.lat as "lat",t1.long as "long",t2.sub as "Base",t1.sub as "Current",t1.sub-t2.sub as "Deviation",cast((t1.sub-t2.sub) as float)/NULLIF(t2.sub,0) as "Deviation%" 
    from (
        select b.time,p.province_name,l.lat as "lat",l.long as "long",sum(c.subscribers_total) as "sub" 
        from (
            select distinct date_trunc('day',a.date) as "day",max(a.date) OVER (PARTITION BY date_trunc('day',a.date)) as "time" 
            from huawei_vlr a
            where date_trunc('day',date) > '2023-05-29'
            order by 1 desc,2 desc
            limit 1
        ) b 
        left join huawei_vlr c on b.time=c.date 
        left join huawei_province p on c.node_name=p.node_name
        left join province_loc l on p.node_name=l.msc_code
        group by 1,2,3,4
        order by 1 desc,2 asc
    ) t1 
    left join (
        select b.time,p.province_name,sum(c.subscribers_total) as "sub" 
        from (
            select distinct date_trunc('day',a.date) as "day",max(a.date) OVER (PARTITION BY date_trunc('day',a.date)) as "time"
            from huawei_vlr a
            where date_trunc('day',date) > '2023-05-29'
            order by 1 desc,2 desc
        ) b 
        left join huawei_vlr c on b.time=c.date 
        left join huawei_province p on c.node_name=p.node_name
        where date_trunc('day',b.time) = '$Base_Date'
        group by 1,2
        order by 1 desc,2 asc
    ) t2 on t1.province_name=t2.province_name
    )
) z1 