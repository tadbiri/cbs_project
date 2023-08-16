<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalFailedCBSCBPDataTehran extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Data Failed Tehran';

        $this->chartLengendHeightPerPixel = 100;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_cbp_data_err_code_log
                         WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         AND cerrc_id <> '1'
                         AND cbp_id = ':cbp_id:'
                         GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                         ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 60*12;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 13;

        $this->entities = [
            ['label'=>'cbp1', 'colorCode'=>'FFBF00', 'param'=>['cbp_id'=>'1'],'description'=>'Tehran CBP1 Total Data Failed'],
            ['label'=>'cbp2', 'colorCode'=>'FFFF00', 'param'=>['cbp_id'=>'2'],'description'=>'Tehran CBP2 Total Data Failed'],
            ['label'=>'cbp3', 'colorCode'=>'BFFF00', 'param'=>['cbp_id'=>'3'],'description'=>'Tehran CBP3 Total Data Failed'],
            ['label'=>'cbp4', 'colorCode'=>'80FF00', 'param'=>['cbp_id'=>'4'],'description'=>'Tehran CBP4 Total Data Failed'],
            ['label'=>'cbp5', 'colorCode'=>'40FF00', 'param'=>['cbp_id'=>'5'],'description'=>'Tehran CBP5 Total Data Failed'],
            ['label'=>'cbp6', 'colorCode'=>'00FF00', 'param'=>['cbp_id'=>'6'],'description'=>'Tehran CBP6 Total Data Failed'],
            ['label'=>'cbp7', 'colorCode'=>'00FF40', 'param'=>['cbp_id'=>'7'],'description'=>'Tehran CBP7 Total Data Failed'],
            ['label'=>'cbp8', 'colorCode'=>'00FF80', 'param'=>['cbp_id'=>'8'],'description'=>'Tehran CBP8 Total Data Failed'],
            ['label'=>'cbp9', 'colorCode'=>'00FFBF', 'param'=>['cbp_id'=>'9'],'description'=>'Tehran CBP9 Total Data Failed'],
            ['label'=>'cbp10', 'colorCode'=>'00FFFF', 'param'=>['cbp_id'=>'10'],'description'=>'Tehran CBP10 Total Data Failed'],
            ['label'=>'cbp11', 'colorCode'=>'00BFFF', 'param'=>['cbp_id'=>'11'],'description'=>'Tehran CBP11 Total Data Failed'],
            ['label'=>'cbp12', 'colorCode'=>'0080FF', 'param'=>['cbp_id'=>'12'],'description'=>'Tehran CBP12 Total Data Failed'],
            ['label'=>'cbp13', 'colorCode'=>'0040FF', 'param'=>['cbp_id'=>'13'],'description'=>'Tehran CBP13 Total Data Failed'],
            ['label'=>'cbp14', 'colorCode'=>'0000FF', 'param'=>['cbp_id'=>'14'],'description'=>'Tehran CBP14 Total Data Failed'],
            ['label'=>'cbp15', 'colorCode'=>'4000FF', 'param'=>['cbp_id'=>'15'],'description'=>'Tehran CBP15 Total Data Failed'],
            ['label'=>'cbp16', 'colorCode'=>'8000FF', 'param'=>['cbp_id'=>'16'],'description'=>'Tehran CBP16 Total Data Failed'],
            ['label'=>'cbp17', 'colorCode'=>'BF00FF', 'param'=>['cbp_id'=>'17'],'description'=>'Tehran CBP17 Total Data Failed'],
            ['label'=>'cbp18', 'colorCode'=>'FF00FF', 'param'=>['cbp_id'=>'18'],'description'=>'Tehran CBP18 Total Data Failed'],
            ['label'=>'cbp19', 'colorCode'=>'FF00BF', 'param'=>['cbp_id'=>'19'],'description'=>'Tehran CBP19 Total Data Failed'],
            ['label'=>'cbp20', 'colorCode'=>'FF0080', 'param'=>['cbp_id'=>'20'],'description'=>'Tehran CBP20 Total Data Failed']
        ];
    }
}
