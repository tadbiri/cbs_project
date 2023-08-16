<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessCBSCBPSMSShiraz extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total SMS Success Shiraz';

        $this->chartLengendHeightPerPixel = 70;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_cbp_sms_err_code_log
                         WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         AND err_code = '0'
                         AND cbp_id = ':cbp_id:'
                         GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                         ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 60*12;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 12;

        $this->entities = [
            ['label'=>'scbp1', 'colorCode'=>'FFBF00', 'param'=>['cbp_id'=>'41'],'description'=>'Shiraz CBP1 Total SMS Success'],
            ['label'=>'scbp2', 'colorCode'=>'FFFF00', 'param'=>['cbp_id'=>'42'],'description'=>'Shiraz CBP2 Total SMS Success'],
            ['label'=>'scbp3', 'colorCode'=>'BFFF00', 'param'=>['cbp_id'=>'43'],'description'=>'Shiraz CBP3 Total SMS Success'],
            ['label'=>'scbp4', 'colorCode'=>'80FF00', 'param'=>['cbp_id'=>'44'],'description'=>'Shiraz CBP4 Total SMS Success'],
            ['label'=>'scbp5', 'colorCode'=>'40FF00', 'param'=>['cbp_id'=>'45'],'description'=>'Shiraz CBP5 Total SMS Success'],
            ['label'=>'scbp6', 'colorCode'=>'00FF00', 'param'=>['cbp_id'=>'46'],'description'=>'Shiraz CBP6 Total SMS Success'],
            ['label'=>'scbp7', 'colorCode'=>'00FF40', 'param'=>['cbp_id'=>'47'],'description'=>'Shiraz CBP7 Total SMS Success'],
            ['label'=>'scbp8', 'colorCode'=>'00FF80', 'param'=>['cbp_id'=>'48'],'description'=>'Shiraz CBP8 Total SMS Success'],
            ['label'=>'scbp9', 'colorCode'=>'00FFBF', 'param'=>['cbp_id'=>'49'],'description'=>'Shiraz CBP9 Total SMS Success'],
            ['label'=>'scbp10', 'colorCode'=>'00FFFF', 'param'=>['cbp_id'=>'50'],'description'=>'Shiraz CBP10 Total SMS Success'],
            ['label'=>'scbp11', 'colorCode'=>'00BFFF', 'param'=>['cbp_id'=>'51'],'description'=>'Shiraz CBP11 Total SMS Success'],
            ['label'=>'scbp12', 'colorCode'=>'0080FF', 'param'=>['cbp_id'=>'52'],'description'=>'Shiraz CBP12 Total SMS Success'],
            ['label'=>'scbp13', 'colorCode'=>'0040FF', 'param'=>['cbp_id'=>'53'],'description'=>'Shiraz CBP13 Total SMS Success'],
            ['label'=>'scbp14', 'colorCode'=>'0000FF', 'param'=>['cbp_id'=>'54'],'description'=>'Shiraz CBP14 Total SMS Success'],
            ['label'=>'scbp15', 'colorCode'=>'4000FF', 'param'=>['cbp_id'=>'55'],'description'=>'Shiraz CBP15 Total SMS Success'],
            ['label'=>'scbp16', 'colorCode'=>'8000FF', 'param'=>['cbp_id'=>'56'],'description'=>'Shiraz CBP16 Total SMS Success']
        ];
    }
}
