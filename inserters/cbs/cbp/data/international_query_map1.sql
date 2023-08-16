select x.time as "time",
CASE 
  WHEN x.node like '%Iraq%' THEN 'Iraq'
  WHEN x.node like '%Iran%' THEN 'Irancell'
  WHEN x.node like '%United Arab Emirates%' THEN 'UAE'
  WHEN x.node like '%Turkey%' THEN 'Turkey'
  WHEN x.node like '%Germany%' THEN 'Germany'
  WHEN x.node like '%United Kingdom%' THEN 'United Kingdom'
  WHEN x.node like '%Oman%' THEN 'Oman'
  WHEN x.node like '%Afghanistan%' THEN 'Afghanistan'
  WHEN x.node like '%Italy%' THEN 'Italy' 
  WHEN x.node like '%Sweden%' THEN 'Sweden'
  WHEN x.node like '%Azerbaijan%' THEN 'Azerbaijan'
  WHEN x.node like '%Qatar%' THEN 'Qatar'
  WHEN x.node like '%Netherlands%' THEN 'Netherlands'
  WHEN x.node like '%Kuwait%' THEN 'Kuwait'
  WHEN x.node like '%New Zealand%' THEN 'New Zealand'
  WHEN x.node like '%Korea S%' THEN 'Korea S'
  WHEN x.node like '%South Africa%' THEN 'South Africa'
  WHEN x.node like '%Saudi Arabia%' THEN 'Saudi Arabia'
  ELSE split_part(x.node,' ',2)
END as "node",
sum(x.e_count) as " " from 
(
    SELECT '2' as "key", a.date_time AS "time",concat(split_part(a.node,'/',1),' ',b.operator_name) as node,a.e_count as "e_count"
    FROM mci_core_kpi_log a
    left join ps_operator_map b on split_part(a.node,':',3)=b.operator_code
    WHERE a."interval" = '30'
    and a.counter = '140509318' 
    and a.e_count <> '0'
    and b.operator_name not like '%MCI%'
    and b.operator_name not like '%Iran%'
) x
where x.key = '$Service'
group by 1,2
order by 1 desc,3 desc

