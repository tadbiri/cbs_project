<?php
    $dbhost = '10.15.90.203';
    $dbname='mci_kpi_db';
    $dbuser = 'root';
    $dbpass = '1qaz@WSX';

    $dbconn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass")
        or die('Could not connect: ' . pg_last_error());

    $query = 'SELECT * FROM mci_core_kpi_log order by date_time desc limit 10';
    $result = pg_query($query) or die('Error message: ' . pg_last_error());

    while ($row = pg_fetch_row($result)) {
        var_dump($row);
    }

    pg_free_result($result);
    pg_close($dbconn);
?>
