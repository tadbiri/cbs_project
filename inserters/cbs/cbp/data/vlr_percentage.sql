    select cast(z1.date2 as date) as "time",z1.province,(z1.used-z2.used2)/z2.used2 as "Deviation%" from (
    select t1.date2,t2.province,sum(t1.used) as "used" from
    (select a.date2,b.switch_name,b.used from
    (select to_char(date_time, 'YYYY-MM-DD'),max(to_char(date_time, 'YYYY-MM-DD HH24:mi')) as "date2"
    from cs_switch
    group by 1) a
    left join (select * from cs_switch) b on a.date2=to_char(b.date_time, 'YYYY-MM-DD HH24:mi')) t1
    left join cs_province t2 on t1.switch_name=t2.msc_code 
    group by 1,2
    order by 1 asc) z1
    left join (
    select t1.date2,t2.province,sum(t1.used) as "used2" from
    (select a.date2,b.switch_name,b.used from
    (select to_char(date_time, 'YYYY-MM-DD'),max(to_char(date_time, 'YYYY-MM-DD HH24:mi')) as "date2"
    from cs_switch
    group by 1) a
    left join (select * from cs_switch) b on a.date2=to_char(b.date_time, 'YYYY-MM-DD HH24:mi')) t1
    left join cs_province t2 on t1.switch_name=t2.msc_code 
    where date2 like '2023-03-03%'
    group by 1,2
    order by 1 asc) z2 on z1.province=z2.province
    where z1.used <> '0'
    and cast(z1.date2 as date) > '2023-03-03'
    
   