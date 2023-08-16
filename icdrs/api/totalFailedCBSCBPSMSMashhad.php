<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalFailedCBSCBPSMSMashhad extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total SMS Failed Mashhad';

        $this->chartLengendHeightPerPixel = 70;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_cbp_sms_err_code_log
                         WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         AND err_code <> '0'
                         AND cbp_id = ':cbp_id:'
                         GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                         ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 100;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 12;

        $this->entities = [
            ['label'=>'mcbp1', 'colorCode'=>'FFBF00', 'param'=>['cbp_id'=>'61'],'description'=>'Mashhad CBP1 Total SMS Failed'],
            ['label'=>'mcbp2', 'colorCode'=>'FFFF00', 'param'=>['cbp_id'=>'62'],'description'=>'Mashhad CBP2 Total SMS Failed'],
            ['label'=>'mcbp3', 'colorCode'=>'BFFF00', 'param'=>['cbp_id'=>'63'],'description'=>'Mashhad CBP3 Total SMS Failed'],
            ['label'=>'mcbp4', 'colorCode'=>'80FF00', 'param'=>['cbp_id'=>'64'],'description'=>'Mashhad CBP4 Total SMS Failed'],
            ['label'=>'mcbp5', 'colorCode'=>'40FF00', 'param'=>['cbp_id'=>'65'],'description'=>'Mashhad CBP5 Total SMS Failed'],
            ['label'=>'mcbp6', 'colorCode'=>'00FF00', 'param'=>['cbp_id'=>'66'],'description'=>'Mashhad CBP6 Total SMS Failed'],
            ['label'=>'mcbp7', 'colorCode'=>'00FF40', 'param'=>['cbp_id'=>'67'],'description'=>'Mashhad CBP7 Total SMS Failed'],
            ['label'=>'mcbp8', 'colorCode'=>'00FF80', 'param'=>['cbp_id'=>'68'],'description'=>'Mashhad CBP8 Total SMS Failed'],
            ['label'=>'mcbp9', 'colorCode'=>'00FFBF', 'param'=>['cbp_id'=>'69'],'description'=>'Mashhad CBP9 Total SMS Failed'],
            ['label'=>'mcbp10', 'colorCode'=>'00FFFF', 'param'=>['cbp_id'=>'70'],'description'=>'Mashhad CBP10 Total SMS Failed'],
            ['label'=>'mcbp11', 'colorCode'=>'00BFFF', 'param'=>['cbp_id'=>'71'],'description'=>'Mashhad CBP11 Total SMS Failed'],
            ['label'=>'mcbp12', 'colorCode'=>'0080FF', 'param'=>['cbp_id'=>'72'],'description'=>'Mashhad CBP12 Total SMS Failed'],
            ['label'=>'mcbp13', 'colorCode'=>'0040FF', 'param'=>['cbp_id'=>'73'],'description'=>'Mashhad CBP13 Total SMS Failed'],
            ['label'=>'mcbp14', 'colorCode'=>'0000FF', 'param'=>['cbp_id'=>'74'],'description'=>'Mashhad CBP14 Total SMS Failed'],
            ['label'=>'mcbp15', 'colorCode'=>'4000FF', 'param'=>['cbp_id'=>'75'],'description'=>'Mashhad CBP15 Total SMS Failed'],
            ['label'=>'mcbp16', 'colorCode'=>'8000FF', 'param'=>['cbp_id'=>'76'],'description'=>'Mashhad CBP16 Total SMS Failed']
        ];
    }
}
