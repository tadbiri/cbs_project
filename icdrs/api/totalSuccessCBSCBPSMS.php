<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessCBSCBPSMS extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total SMS Success';

        $this->chartLengendHeightPerPixel = 20;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_cbp_sms_err_code_log
                         WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         AND err_code = '0'
                         AND region_id = ':region_id:'
                         GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                         ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 30;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 12;

        $this->entities = [
            ['label'=>'Tehran', 'colorCode'=>'FB56B7', 'param'=>['region_id'=>'1'], 'description'=>'Tehran Total SMS Success'],
            ['label'=>'Tabriz', 'colorCode'=>'009900', 'param'=>['region_id'=>'2'], 'description'=>'Tabriz Total SMS Success'],
            ['label'=>'Shiraz', 'colorCode'=>'FEBF05', 'param'=>['region_id'=>'3'], 'description'=>'Shiraz Total SMS Success'],
            ['label'=>'Mashhad', 'colorCode'=>'008CFF', 'param'=>['region_id'=>'4'], 'description'=>'Mashhad Total SMS Success']
        ];
    }
}
