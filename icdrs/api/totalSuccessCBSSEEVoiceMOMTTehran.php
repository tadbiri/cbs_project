<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessCBSSEEVoiceMOMTTehran extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Success Tehran';

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
            ['label'=>'see1', 'colorCode'=>'D3005E', 'param'=>['see_id'=>'1'],'description'=>'Tehran SEE1 Total MO/MT/FW Success'],
            ['label'=>'see2', 'colorCode'=>'C53A98', 'param'=>['see_id'=>'2'],'description'=>'Tehran SEE2 Total MO/MT/FW Success'],
            ['label'=>'see3', 'colorCode'=>'8B4FA3', 'param'=>['see_id'=>'3'],'description'=>'Tehran SEE3 Total MO/MT/FW Success'],
            ['label'=>'see4', 'colorCode'=>'FF6795', 'param'=>['see_id'=>'4'],'description'=>'Tehran SEE4 Total MO/MT/FW Success'],
            ['label'=>'see5', 'colorCode'=>'FF66C3', 'param'=>['see_id'=>'5'],'description'=>'Tehran SEE5 Total MO/MT/FW Success'],
            ['label'=>'see6', 'colorCode'=>'FAA0FF', 'param'=>['see_id'=>'6'],'description'=>'Tehran SEE6 Total MO/MT/FW Success']
        ];
    }
}
