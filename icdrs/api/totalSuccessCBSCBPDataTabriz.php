<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessCBSCBPDataTabriz extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Data Success Tabriz';

        $this->chartLengendHeightPerPixel = 70;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_cbp_data_err_code_log
                         WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         AND cerrc_id = '1'
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
            ['label'=>'tcbp1', 'colorCode'=>'FFBF00', 'param'=>['cbp_id'=>'21'],'description'=>'Tabriz CBP1 Total Data Success'],
            ['label'=>'tcbp2', 'colorCode'=>'FFFF00', 'param'=>['cbp_id'=>'22'],'description'=>'Tabriz CBP2 Total Data Success'],
            ['label'=>'tcbp3', 'colorCode'=>'BFFF00', 'param'=>['cbp_id'=>'23'],'description'=>'Tabriz CBP3 Total Data Success'],
            ['label'=>'tcbp4', 'colorCode'=>'80FF00', 'param'=>['cbp_id'=>'24'],'description'=>'Tabriz CBP4 Total Data Success'],
            ['label'=>'tcbp5', 'colorCode'=>'40FF00', 'param'=>['cbp_id'=>'25'],'description'=>'Tabriz CBP5 Total Data Success'],
            ['label'=>'tcbp6', 'colorCode'=>'00FF00', 'param'=>['cbp_id'=>'26'],'description'=>'Tabriz CBP6 Total Data Success'],
            ['label'=>'tcbp7', 'colorCode'=>'00FF40', 'param'=>['cbp_id'=>'27'],'description'=>'Tabriz CBP7 Total Data Success'],
            ['label'=>'tcbp8', 'colorCode'=>'00FF80', 'param'=>['cbp_id'=>'28'],'description'=>'Tabriz CBP8 Total Data Success'],
            ['label'=>'tcbp9', 'colorCode'=>'00FFBF', 'param'=>['cbp_id'=>'29'],'description'=>'Tabriz CBP9 Total Data Success'],
            ['label'=>'tcbp10', 'colorCode'=>'00FFFF', 'param'=>['cbp_id'=>'30'],'description'=>'Tabriz CBP10 Total Data Success'],
            ['label'=>'tcbp11', 'colorCode'=>'00BFFF', 'param'=>['cbp_id'=>'31'],'description'=>'Tabriz CBP11 Total Data Success'],
            ['label'=>'tcbp12', 'colorCode'=>'0080FF', 'param'=>['cbp_id'=>'32'],'description'=>'Tabriz CBP12 Total Data Success'],
            ['label'=>'tcbp13', 'colorCode'=>'0040FF', 'param'=>['cbp_id'=>'33'],'description'=>'Tabriz CBP13 Total Data Success'],
            ['label'=>'tcbp14', 'colorCode'=>'0000FF', 'param'=>['cbp_id'=>'34'],'description'=>'Tabriz CBP14 Total Data Success'],
            ['label'=>'tcbp15', 'colorCode'=>'4000FF', 'param'=>['cbp_id'=>'35'],'description'=>'Tabriz CBP15 Total Data Success'],
            ['label'=>'tcbp16', 'colorCode'=>'8000FF', 'param'=>['cbp_id'=>'36'],'description'=>'Tabriz CBP16 Total Data Success']
        ];
    }
}
