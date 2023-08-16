<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalFailedCBSSEEVoiceMGM extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Management Failed';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                        FROM cbs_see_voice_err_code_log
                        WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                        AND serrc_code not in ('99,100')
                        AND subkey_code = '5'
                        AND region_id = ':region_id:'
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
            ['label'=>'Tehran', 'colorCode'=>'FF0000', 'param'=>['region_id'=>'1'], 'description'=>'Tehran Total MGM Failed'],
            ['label'=>'Tabriz', 'colorCode'=>'0000FF', 'param'=>['region_id'=>'2'], 'description'=>'Tabriz Total MGM Failed'],
            ['label'=>'Shiraz', 'colorCode'=>'176b2a', 'param'=>['region_id'=>'3'], 'description'=>'Shiraz Total MGM Failed'],
            ['label'=>'Mashhad', 'colorCode'=>'dbbb18', 'param'=>['region_id'=>'4'], 'description'=>'Mashhad Total MGM Failed']
        ];
    }
}
