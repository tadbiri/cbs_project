<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart1.php";

class CBSCBPVoiceCellTotal extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Iran Voice';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "set statement_timeout= '600000s';
                        SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(:unit:) AS \"errorCount\"
                        FROM cbs_cbp_rec_cell_log
                        WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
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
            ['label'=>'call_min', 'colorCode'=>'FB56B7', 'param'=>['unit'=>'call_min'], 'description'=>'Iran Total call_min'],
            ['label'=>'call_min_free', 'colorCode'=>'009900', 'param'=>['unit'=>'call_min_free'], 'description'=>'Iran Total call_min_free'],
            ['label'=>'call_debit_amount', 'colorCode'=>'FEBF05', 'param'=>['unit'=>'call_debit_amount'], 'description'=>'Iran Total call_debit_amount'],
            ['label'=>'call_free_amount', 'colorCode'=>'008CFF', 'param'=>['unit'=>'call_free_amount'], 'description'=>'Iran Total call_free_amount']
        ];
    }
}
