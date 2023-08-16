<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalFailedCBSSEEVoiceMGMShiraz extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Management Failed Shiraz';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                        FROM cbs_see_voice_err_code_log
                        WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                        AND serrc_code not in ('99,100')
                        AND subkey_code = '5'
                        AND see_id = ':see_id:'
                        GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                        ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 60;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 3;

        $this->entities = [
            ['label'=>'ssee1', 'colorCode'=>'4165f5', 'param'=>['see_id'=>'13'],'description'=>'Shiraz SEE1 Total MGM Failed'],
            ['label'=>'ssee2', 'colorCode'=>'e4532f', 'param'=>['see_id'=>'14'],'description'=>'Shiraz SEE2 Total MGM Failed'],
            ['label'=>'ssee3', 'colorCode'=>'67ac11', 'param'=>['see_id'=>'15'],'description'=>'Shiraz SEE3 Total MGM Failed'],
            ['label'=>'ssee4', 'colorCode'=>'9123bc', 'param'=>['see_id'=>'16'],'description'=>'Shiraz SEE4 Total MGM Failed'],
            ['label'=>'ssee5', 'colorCode'=>'dbbb18', 'param'=>['see_id'=>'17'],'description'=>'Shiraz SEE5 Total MGM Failed'],
            ['label'=>'ssee6', 'colorCode'=>'c30f00', 'param'=>['see_id'=>'18'],'description'=>'Shiraz SEE6 Total MGM Failed']
        ];
    }
}