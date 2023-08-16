<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart1.php";

class CBSCBPDataCellTotalRgKSA extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Data KSA';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "set statement_timeout= '600000s';
                        commit;
                        SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(actual_usage) AS \"errorCount\"
                        FROM cbs_cbp_data_cell_log
                        WHERE city_code = 'r966'
                        and rg = ':rg:'
                        AND cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
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
            ['label'=>'rg80', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'80'], 'description'=>'Rubika PKG free KSA'],
            ['label'=>'rg81', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'81'], 'description'=>'Rubika PAYG free KSA'],
            ['label'=>'rg9', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'9'], 'description'=>'International Roaming'],
            ['label'=>'rg90', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'90'], 'description'=>'International Roaming Usage on Package and PAYG Free (Shop/MyMCI /Bank GWs) Zero'],
        ];
    }
}
