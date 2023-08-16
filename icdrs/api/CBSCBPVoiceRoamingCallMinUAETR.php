<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart1.php";

class CBSCBPVoiceRoamingCallMinUAETR extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Roaming Call Min';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "set statement_timeout= '600000s';
                        SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(call_min)/60 AS \"errorCount\"
                        FROM cbs_cbp_rec_cell_log
                        WHERE area_code = ':province:'
                        And cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                        GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                        ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 60;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 0;

        $this->deleteCoefficient = 0.2;

        $this->entities = [
            ['label'=>'971', 'colorCode'=>'FFFF00', 'param'=>['province'=>'r971'], 'description'=>'Total actual_usage United Arab Emirates'],
            ['label'=>'90', 'colorCode'=>'BFFF00', 'param'=>['province'=>'r90'], 'description'=>'Total actual_usage Turkey'],
        ];
    }
}
