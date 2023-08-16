<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart1.php";

class CBSCBPDataCellTotalRgSis extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Sistan Data RG';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "set statement_timeout= '600000s';
                        commit;
                        SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", sum(actual_usage) AS \"errorCount\"
                        FROM cbs_cbp_data_cell_log
                        WHERE city_code = '54' 
                        and rg = ':rg:'
                        and cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
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
            ['label'=>'rg2', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'2'], 'description'=>'RG'],
            ['label'=>'rg3', 'colorCode'=>'009900', 'param'=>['rg'=>'3'], 'description'=>'RG'],
            ['label'=>'rg4', 'colorCode'=>'FEBF05', 'param'=>['rg'=>'4'], 'description'=>'RG'],
            ['label'=>'rg5', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'5'], 'description'=>'RG'],
            ['label'=>'rg6', 'colorCode'=>'009900', 'param'=>['rg'=>'6'], 'description'=>'RG'],
            ['label'=>'rg9', 'colorCode'=>'FEBF05', 'param'=>['rg'=>'9'], 'description'=>'RG'],
            ['label'=>'rg10', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'10'], 'description'=>'RG'],
            ['label'=>'rg11', 'colorCode'=>'009900', 'param'=>['rg'=>'11'], 'description'=>'RG'],
            ['label'=>'rg90', 'colorCode'=>'FEBF05', 'param'=>['rg'=>'90'], 'description'=>'RG'],
            ['label'=>'rg91', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'91'], 'description'=>'RG'],
            ['label'=>'rg96', 'colorCode'=>'009900', 'param'=>['rg'=>'96'], 'description'=>'RG'],
            ['label'=>'rg98', 'colorCode'=>'FEBF05', 'param'=>['rg'=>'98'], 'description'=>'RG'],
            ['label'=>'rg99', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'99'], 'description'=>'RG'],
            ['label'=>'rg100', 'colorCode'=>'009900', 'param'=>['rg'=>'100'], 'description'=>'RG'],
            ['label'=>'rg106', 'colorCode'=>'FEBF05', 'param'=>['rg'=>'106'], 'description'=>'RG'],
            ['label'=>'rg151', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'151'], 'description'=>'RG'],
            ['label'=>'rg302', 'colorCode'=>'009900', 'param'=>['rg'=>'302'], 'description'=>'RG'],
            ['label'=>'rg3000', 'colorCode'=>'FEBF05', 'param'=>['rg'=>'3000'], 'description'=>'RG'],
            ['label'=>'rg3001', 'colorCode'=>'FEBF05', 'param'=>['rg'=>'3001'], 'description'=>'RG'],
            ['label'=>'rg3500', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'3500'], 'description'=>'RG'],
            ['label'=>'rg3501', 'colorCode'=>'009900', 'param'=>['rg'=>'3501'], 'description'=>'RG'],
            ['label'=>'rg4001', 'colorCode'=>'FEBF05', 'param'=>['rg'=>'4001'], 'description'=>'RG'],
            ['label'=>'rg4101', 'colorCode'=>'FB56B7', 'param'=>['rg'=>'4101'], 'description'=>'RG'],
            ['label'=>'rg4501', 'colorCode'=>'009900', 'param'=>['rg'=>'4501'], 'description'=>'RG'],
            ['label'=>'rg4599', 'colorCode'=>'FEBF05', 'param'=>['rg'=>'4599'], 'description'=>'RG'],
        ];
    }
}
