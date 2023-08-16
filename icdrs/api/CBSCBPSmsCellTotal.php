<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart1.php";

class CBSCBPSmsCellTotal extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Iran SMS Analysis';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "set statement_timeout= '600000s';
                        commit;
                        SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(:unit:) AS \"errorCount\"
                        FROM cbs_cbp_sms_cell_log
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
            ['label'=>'actualusage', 'colorCode'=>'FB56B7', 'param'=>['unit'=>'e_count'], 'description'=>'Iran Total actual_usage'],
            ['label'=>'debitamount', 'colorCode'=>'009900', 'param'=>['unit'=>'debit_amount'], 'description'=>'Iran Total debit_amount'],
            ['label'=>'freeamount', 'colorCode'=>'FEBF05', 'param'=>['unit'=>'free_amount'], 'description'=>'Iran Total free_amount']
        ];
    }
}
