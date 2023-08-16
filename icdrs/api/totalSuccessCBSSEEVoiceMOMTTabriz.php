<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessCBSSEEVoiceMOMTTabriz extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Success Tabriz';

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
            ['label'=>'tsee1', 'colorCode'=>'00984C', 'param'=>['see_id'=>'7'],'description'=>'Tabriz SEE1 Total MO/MT/FW Success'],
            ['label'=>'tsee2', 'colorCode'=>'008D49', 'param'=>['see_id'=>'8'],'description'=>'Tabriz SEE2 Total MO/MT/FW Success'],
            ['label'=>'tsee3', 'colorCode'=>'005F00', 'param'=>['see_id'=>'9'],'description'=>'Tabriz SEE3 Total MO/MT/FW Success'],
            ['label'=>'tsee4', 'colorCode'=>'007E6A', 'param'=>['see_id'=>'10'],'description'=>'Tabriz SEE4 Total MO/MT/FW Success'],
            ['label'=>'tsee5', 'colorCode'=>'006D77', 'param'=>['see_id'=>'11'],'description'=>'Tabriz SEE5 Total MO/MT/FW Success'],
            ['label'=>'tsee6', 'colorCode'=>'005A70', 'param'=>['see_id'=>'12'],'description'=>'Tabriz SEE6 Total MO/MT/FW Success']
        ];
    }
}