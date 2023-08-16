<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalFailedCBSSEEVoiceMGMTehran extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Management Failed Tehran';

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
            ['label'=>'see1', 'colorCode'=>'4165f5', 'param'=>['see_id'=>'1'],'description'=>'Tehran SEE1 Total MGM Failed'],
            ['label'=>'see2', 'colorCode'=>'e4532f', 'param'=>['see_id'=>'2'],'description'=>'Tehran SEE2 Total MGM Failed'],
            ['label'=>'see3', 'colorCode'=>'67ac11', 'param'=>['see_id'=>'3'],'description'=>'Tehran SEE3 Total MGM Failed'],
            ['label'=>'see4', 'colorCode'=>'c30f00', 'param'=>['see_id'=>'4'],'description'=>'Tehran SEE4 Total MGM Failed'],
            ['label'=>'see5', 'colorCode'=>'dbbb18', 'param'=>['see_id'=>'5'],'description'=>'Tehran SEE5 Total MGM Failed'],
            ['label'=>'see6', 'colorCode'=>'9123bc', 'param'=>['see_id'=>'6'],'description'=>'Tehran SEE6 Total MGM Failed']
        ];
    }
}
