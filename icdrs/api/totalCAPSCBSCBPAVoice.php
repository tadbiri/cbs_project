<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalCAPSCBSCBPAVoice extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Voice CAPS';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Gap;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                        FROM cbs_cbpa_voice_log
                        WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                        AND event_type_id = '1'
                        AND cbp_id = ':cbp_id:'
                        GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                        ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 50;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 1;

        $this->deleteCoefficient = 0.2;

        $this->entities = [
            ['label'=>'CBPA1', 'colorCode'=>'c4162a', 'param'=>['cbp_id'=>'451'], 'description'=>'Tehran CAPS VOICE CBPA1', 'analytic'=>['colorCode'=>'FB56B7','description'=>'CBPA1 inc/dec rate',]],
            ['label'=>'CBPA2', 'colorCode'=>'f2495d', 'param'=>['cbp_id'=>'455'], 'description'=>'Tehran CAPS VOICE CBPA2', 'analytic'=>['colorCode'=>'FB56B7','description'=>'CBPA2 inc/dec rate',]],
            ['label'=>'TCBPA1', 'colorCode'=>'38872d', 'param'=>['cbp_id'=>'453'], 'description'=>'Tabriz CAPS VOICE CBPA1', 'analytic'=>['colorCode'=>'009900','description'=>'TCBPA1 inc/dec rate',]],
            ['label'=>'TCBPA2', 'colorCode'=>'73bf69', 'param'=>['cbp_id'=>'457'], 'description'=>'Tabriz CAPS VOICE CBPA2', 'analytic'=>['colorCode'=>'009900','description'=>'TCBPA2 inc/dec rate',]],
            ['label'=>'SCBPA1', 'colorCode'=>'e0b300', 'param'=>['cbp_id'=>'452'], 'description'=>'Shiraz CAPS VOICE CBPA1', 'analytic'=>['colorCode'=>'FEBF05','description'=>'SCBPA1 inc/dec rate',]],
            ['label'=>'SCBPA2', 'colorCode'=>'fade2a', 'param'=>['cbp_id'=>'456'], 'description'=>'Shiraz CAPS VOICE CBPA2', 'analytic'=>['colorCode'=>'FEBF05','description'=>'SCBPA2 inc/dec rate',]],
            ['label'=>'MCBPA1', 'colorCode'=>'1f61c4', 'param'=>['cbp_id'=>'454'], 'description'=>'Mashhad CAPS VOICE CBPA1', 'analytic'=>['colorCode'=>'008CFF','description'=>'MCBPA1 inc/dec rate']],
            ['label'=>'MCBPA2', 'colorCode'=>'5795f2', 'param'=>['cbp_id'=>'458'], 'description'=>'Mashhad CAPS VOICE CBPA2', 'analytic'=>['colorCode'=>'008CFF','description'=>'MCBPA2 inc/dec rate']]
        ];
    }
}
