<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessCBSSEEVoiceMOMTMashhad extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Success Mashhad';

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
            ['label'=>'msee1', 'colorCode'=>'0071FF', 'param'=>['see_id'=>'19'],'description'=>'Mashhad SEE1 Total MO/MT/FW Success'],
            ['label'=>'msee2', 'colorCode'=>'0071FF', 'param'=>['see_id'=>'20'],'description'=>'Mashhad SEE2 Total MO/MT/FW Success'],
            ['label'=>'msee3', 'colorCode'=>'1A3EFF', 'param'=>['see_id'=>'21'],'description'=>'Mashhad SEE3 Total MO/MT/FW Success'],
            ['label'=>'msee4', 'colorCode'=>'2D4DF5', 'param'=>['see_id'=>'22'],'description'=>'Mashhad SEE4 Total MO/MT/FW Success'],
            ['label'=>'msee5', 'colorCode'=>'302Df5', 'param'=>['see_id'=>'23'],'description'=>'Mashhad SEE5 Total MO/MT/FW Success'],
            ['label'=>'msee6', 'colorCode'=>'4B41BC', 'param'=>['see_id'=>'24'],'description'=>'Mashhad SEE6 Total MO/MT/FW Success']
        ];
    }
}
