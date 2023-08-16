<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalFailedCBSCBPData extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Data Failed';

        $this->chartLengendHeightPerPixel = 20;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_cbp_data_err_code_log
                         WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         AND cerrc_id <> '1'
                         AND region_id = ':region_id:'
                         GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                         ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 60;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 40;

        $this->entities = [
            ['label'=>'Tehran', 'colorCode'=>'FB56B7', 'param'=>['region_id'=>'1'], 'description'=>'Tehran Total Data Failed'],
            ['label'=>'Tabriz', 'colorCode'=>'009900', 'param'=>['region_id'=>'2'], 'description'=>'Tabriz Total Data Failed'],
            ['label'=>'Shiraz', 'colorCode'=>'FEBF05', 'param'=>['region_id'=>'3'], 'description'=>'Shiraz Total Data Failed'],
            ['label'=>'Mashhad', 'colorCode'=>'008CFF', 'param'=>['region_id'=>'4'], 'description'=>'Mashhad Total Data Failed']
        ];
    }
}
