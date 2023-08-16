<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalFailedCBSSEERecCallTypeShiraz extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'Totall CBS Failed Call Type Shiraz';

        $this->chartLengendHeightPerPixel = 55;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                        FROM cbs_see_rec_msc_log
                        WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                        AND termination_reason_id <> '1'
                        AND ml.region_id = '3'
                        AND call_type_id = ':call_type_id:'
                        GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                        ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";
        
        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 24*60;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 100;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 3;

        $this->entities = [
            ['label'=>'international', 'colorCode'=>'9B0085', 'param'=>['call_type_id'=>'1'], 'description'=>'international toll call'],
            ['label'=>'toll call between charging areas', 'colorCode'=>'C039A7', 'param'=>['call_type_id'=>'2'], 'description'=>'toll call between charging areas'],
            ['label'=>'toll call within a charging area', 'colorCode'=>'E75FCB', 'param'=>['call_type_id'=>'3'], 'description'=>'toll call within a charging area'],
            ['label'=>'local call', 'colorCode'=>'0079EF', 'param'=>['call_type_id'=>'4'], 'description'=>'local call'],
        ];
    }
}
