<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessCBSSEEVoiceMOMT extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Success';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                        FROM cbs_see_voice_err_code_log
                        WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                        AND serrc_code = '100'
                        AND region_id = ':region_id:'
                        AND subkey_code in (1,2,3,21,22,23,31,32,33,80,81,83)
                        GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                        ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 50;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 3;

        $this->deleteCoefficient = 0.2;

        $this->entities = [
            ['label'=>'Tehran', 'colorCode'=>'FB56B7', 'param'=>['region_id'=>'1'], 'description'=>'Tehran Total MO/MT/FW Success', 'analytic'=>['colorCode'=>'FB56B7','description'=>'Tehran inc/dec rate',]],
            ['label'=>'Tabriz', 'colorCode'=>'009900', 'param'=>['region_id'=>'2'], 'description'=>'Tabriz Total MO/MT/FW Success', 'analytic'=>['colorCode'=>'009900','description'=>'Tabriz inc/dec rate',]],
            ['label'=>'Shiraz', 'colorCode'=>'FEBF05', 'param'=>['region_id'=>'3'], 'description'=>'Shiraz Total MO/MT/FW Success', 'analytic'=>['colorCode'=>'FEBF05','description'=>'Shiraz inc/dec rate',]],
            ['label'=>'Mashhad', 'colorCode'=>'008CFF', 'param'=>['region_id'=>'4'], 'description'=>'Mashhad Total MO/MT/FW Success', 'analytic'=>['colorCode'=>'008CFF','description'=>'Mashhad inc/dec rate']]
        ];
    }
}
