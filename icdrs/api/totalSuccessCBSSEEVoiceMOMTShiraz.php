<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessCBSSEEVoiceMOMTShiraz extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Success Shiraz';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                        FROM cbs_see_voice_err_code_log
                        WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                        AND serrc_code = '100'
                        AND see_id = ':see_id:'
                        AND subkey_code in (1,2,3,21,22,23,31,32,33,80,81,83)
                        GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                        ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 30;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 3;

        $this->entities = [
            ['label'=>'ssee1', 'colorCode'=>'F8BA00', 'param'=>['see_id'=>'13'],'description'=>'Shiraz SEE1 Total MO/MT/FW Success'],
            ['label'=>'ssee2', 'colorCode'=>'FEAE05', 'param'=>['see_id'=>'14'],'description'=>'Shiraz SEE2 Total MO/MT/FW Success'],
            ['label'=>'ssee3', 'colorCode'=>'FEBF05', 'param'=>['see_id'=>'15'],'description'=>'Shiraz SEE3 Total MO/MT/FW Success'],
            ['label'=>'ssee4', 'colorCode'=>'CB9400', 'param'=>['see_id'=>'16'],'description'=>'Shiraz SEE4 Total MO/MT/FW Success'],
            ['label'=>'ssee5', 'colorCode'=>'9A6B00', 'param'=>['see_id'=>'17'],'description'=>'Shiraz SEE5 Total MO/MT/FW Success'],
            ['label'=>'ssee6', 'colorCode'=>'6E4400', 'param'=>['see_id'=>'18'],'description'=>'Shiraz SEE6 Total MO/MT/FW Success']
        ];
    }
}