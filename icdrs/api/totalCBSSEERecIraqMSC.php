totalCBSSEERecIraqMSC<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalCBSSEERecIraqMSC extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'Iraq MSC Success';

        $this->chartLengendHeightPerPixel = 55;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT SUBSTRING_INDEX(ml.cdr_date_time,':',2) AS 'dateTime', 
                        SUM(ml.e_count) AS 'errorCount' 
                        FROM cbs_see_rec_msc_log ml
                        WHERE SUBSTRING_INDEX(ml.cdr_date_time,'.',1) BETWEEN ':startTime:' AND ':endTime:'
                        AND ml.msc_id = ':msc_id:'
                        GROUP BY SUBSTRING_INDEX(ml.cdr_date_time,':',2)";
        
        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 24*60;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 100;

        $this->waitForFetchAgainBasedSecond = 1; 

        $this->chartMinuteShift = 5;

        $this->entities = [
            ['label'=>'Asia', 'colorCode'=>'9B0085', 'param'=>['msc_id'=>'193','194','197'], 'description'=>'AsiaCell'],
            //['label'=>'Asia B', 'colorCode'=>'C039A7', 'param'=>['msc_id'=>'194'], 'description'=>'MSAHB 989110300'],
            //['label'=>'Asia C', 'colorCode'=>'E75FCB', 'param'=>['msc_id'=>'197'], 'description'=>'MSAHC 989110700'],
        ];
    }
}
