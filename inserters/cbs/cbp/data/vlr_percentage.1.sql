select cast(concat(substring(cast([datetime2] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' as "time",
    cast((sum(z1.used)-(select sum(z1.used) from (
            select t1.datetime2,sum(t2.used) as used from (
                select cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' as datetime1 ,max(cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') AS datetime2
                from nokia_sw1
                group by cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time'
            ) as t1
            left join (select (cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') as datetime3,used from nokia_sw1 ) t2
            on t1.datetime2=t2.datetime3
            group by t1.datetime2
            union ALL
            (
                select t1.datetime2,sum(t2.used) from (
                    select cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' as datetime1 ,max(cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') AS datetime2
                    from huawei_sw
                    group by cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time'
                ) as t1
                left join (select (cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') as datetime3,used from huawei_sw ) t2
                on t1.datetime2=t2.datetime3
                group by t1.datetime2
            )
        ) as z1
        where cast(concat(substring(cast([datetime2] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' like '2023-03-03%'
        group by cast(concat(substring(cast([datetime2] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time')
    ) as float)
    /(select sum(z1.used) from (
            select t1.datetime2,sum(t2.used) as used from (
                select cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' as datetime1 ,max(cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') AS datetime2
                from nokia_sw1
                group by cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time'
            ) as t1
            left join (
                select (cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') as datetime3,used from nokia_sw1 
            ) t2 on t1.datetime2=t2.datetime3
            group by t1.datetime2
            union ALL(
                select t1.datetime2,sum(t2.used) from (
                    select cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' as datetime1 ,max(cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') AS datetime2
                    from huawei_sw
                    group by cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time'
                ) as t1
                left join (select (cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') as datetime3,used from huawei_sw ) t2
                on t1.datetime2=t2.datetime3
                group by t1.datetime2
            )
        ) as z1
        where cast(concat(substring(cast([datetime2] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' like '2023-03-03%'
        group by cast(concat(substring(cast([datetime2] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time'
    )
from (
    select t1.datetime2,sum(t2.used) as used from (
        select cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' as datetime1 ,max(cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') AS datetime2
        from nokia_sw1
        group by cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time'
    ) as t1
    left join (select (cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') as datetime3,used from nokia_sw1 ) t2
    on t1.datetime2=t2.datetime3
    group by t1.datetime2
    union ALL
    (
    select t1.datetime2,sum(t2.used) from (
    select cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' as datetime1 ,max(cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') AS datetime2
    from huawei_sw
    group by cast(concat(substring(cast([Date1] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time') as t1
    left join (select (cast(concat(substring(cast([Date1] as char),1,14),'00') as datetime) at time zone 'Iran Standard Time') as datetime3,used from huawei_sw ) t2
    on t1.datetime2=t2.datetime3
    )
    group by t1.datetime2
) as z1
where cast(concat(substring(cast([datetime2] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' > '2023-02-27'
group by cast(concat(substring(cast([datetime2] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time'
order by cast(concat(substring(cast([datetime2] as char),1,11),'00:00') as datetime) at time zone 'Iran Standard Time' asc