select t1.time,t1.province, t1.live/greatest(t1.live,t2."1w",t3."2w",t4."3w",t5."4w",t6."5w",t7."6w",t8."7w",t9."8w") as " "  from 
(SELECT date_trunc('hour',a.date_time) as "time",p.province_name as "province",max(a.cell_count) as "live"
FROM cbs_cell_availability_province a
left join province_loc p on a.province_code=p.province_code
where p.province_name <> '1'
group by 1,2
order by 1 asc) t1
left join 
(SELECT date_trunc('hour',a.date_time) + interval '7 day' as "time",p.province_name as "province",max(a.cell_count) as "1w"
FROM cbs_cell_availability_province a
left join province_loc p on a.province_code=p.province_code
where p.province_name <> '1'
and a.date_time < now() - interval '7 day'
group by 1,2
order by 1 asc) t2 on t1.time=t2.time and t1.province=t2.province
left join 
(SELECT date_trunc('hour',a.date_time) + interval '14 day' as "time",p.province_name as "province",cast(max(a.cell_count) as float) as "2w"
FROM cbs_cell_availability_province a
left join province_loc p on a.province_code=p.province_code
where p.province_name <> '1'
and a.date_time < now() - interval '14 day'
group by 1,2
order by 1 asc) t3 on t1.time=t3.time and t1.province=t3.province
left join 
(SELECT date_trunc('hour',a.date_time) + interval '21 day' as "time",p.province_name as "province",cast(max(a.cell_count) as float) as "3w"
FROM cbs_cell_availability_province a
left join province_loc p on a.province_code=p.province_code
where p.province_name <> '1'
and a.date_time < now() - interval '21 day'
group by 1,2
order by 1 asc) t4 on t1.time=t4.time and t1.province=t4.province
left join 
(SELECT date_trunc('hour',a.date_time) + interval '28 day' as "time",p.province_name as "province",cast(max(a.cell_count) as float) as "4w"
FROM cbs_cell_availability_province a
left join province_loc p on a.province_code=p.province_code
where p.province_name <> '1'
and a.date_time < now() - interval '28 day'
group by 1,2
order by 1 asc) t5 on t1.time=t5.time and t1.province=t5.province
left join 
(SELECT date_trunc('hour',a.date_time) + interval '35 day' as "time",p.province_name as "province",cast(max(a.cell_count) as float) as "5w"
FROM cbs_cell_availability_province a
left join province_loc p on a.province_code=p.province_code
where p.province_name <> '1'
and a.date_time < now() - interval '35 day'
group by 1,2
order by 1 asc) t6 on t1.time=t6.time and t1.province=t6.province
left join 
(SELECT date_trunc('hour',a.date_time) + interval '42 day' as "time",p.province_name as "province",cast(max(a.cell_count) as float) as "6w"
FROM cbs_cell_availability_province a
left join province_loc p on a.province_code=p.province_code
where p.province_name <> '1'
and a.date_time < now() - interval '42 day'
group by 1,2
order by 1 asc) t7 on t1.time=t7.time and t1.province=t7.province
left join 
(SELECT date_trunc('hour',a.date_time) + interval '49 day' as "time",p.province_name as "province",cast(max(a.cell_count) as float) as "7w"
FROM cbs_cell_availability_province a
left join province_loc p on a.province_code=p.province_code
where p.province_name <> '1'
and a.date_time < now() - interval '49 day'
group by 1,2
order by 1 asc) t8 on t1.time=t8.time and t1.province=t8.province
left join 
(SELECT date_trunc('hour',a.date_time) + interval '56 day' as "time",p.province_name as "province",cast(max(a.cell_count) as float) as "8w"
FROM cbs_cell_availability_province a
left join province_loc p on a.province_code=p.province_code
where p.province_name <> '1'
and a.date_time < now() - interval '56 day'
group by 1,2
order by 1 asc) t9 on t1.time=t9.time and t1.province=t9.province
order by 1,2,3 asc