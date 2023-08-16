<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart1.php";

class CBSCBPVoiceCellProvinceCallAmount extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Province Call Amount';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "set statement_timeout= '600000s';
                        SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", (SUM(call_debit_amount)+SUM(call_free_amount))/10 AS \"errorCount\"
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
            ['label'=>'21', 'colorCode'=>'FB56B7', 'param'=>['province'=>'21'], 'description'=>'Total call_min tehran'],
            ['label'=>'31', 'colorCode'=>'FB56B7', 'param'=>['province'=>'31'], 'description'=>'Total call_min Esfahan'],
            ['label'=>'11', 'colorCode'=>'FB56B7', 'param'=>['province'=>'11'], 'description'=>'Total call_min Mazandaran'],
            ['label'=>'13', 'colorCode'=>'FB56B7', 'param'=>['province'=>'13'], 'description'=>'Total call_min Gilan'],
            ['label'=>'17', 'colorCode'=>'FB56B7', 'param'=>['province'=>'17'], 'description'=>'Total call_min Golestan'],
            ['label'=>'23', 'colorCode'=>'FB56B7', 'param'=>['province'=>'23'], 'description'=>'Total call_min Semnan'],
            ['label'=>'24', 'colorCode'=>'FB56B7', 'param'=>['province'=>'24'], 'description'=>'Total call_min Zanjan'],
            ['label'=>'25', 'colorCode'=>'FB56B7', 'param'=>['province'=>'25'], 'description'=>'Total call_min Qom'],
            ['label'=>'26', 'colorCode'=>'FB56B7', 'param'=>['province'=>'26'], 'description'=>'Total call_min Alborz'],
            ['label'=>'28', 'colorCode'=>'FB56B7', 'param'=>['province'=>'28'], 'description'=>'Total call_min Qazvin'],
            ['label'=>'34', 'colorCode'=>'FB56B7', 'param'=>['province'=>'34'], 'description'=>'Total call_min Kerman'],
            ['label'=>'35', 'colorCode'=>'FB56B7', 'param'=>['province'=>'35'], 'description'=>'Total call_min Yazd'],
            ['label'=>'38', 'colorCode'=>'FB56B7', 'param'=>['province'=>'38'], 'description'=>'Total call_min Charmahal'],
            ['label'=>'41', 'colorCode'=>'FB56B7', 'param'=>['province'=>'41'], 'description'=>'Total call_min azarbayejan east'],
            ['label'=>'44', 'colorCode'=>'FB56B7', 'param'=>['province'=>'44'], 'description'=>'Total call_min Azarbayejan west'],
            ['label'=>'45', 'colorCode'=>'FB56B7', 'param'=>['province'=>'45'], 'description'=>'Total call_min Ardabil'],
            ['label'=>'51', 'colorCode'=>'FB56B7', 'param'=>['province'=>'51'], 'description'=>'Total call_min Mashhhad'],
            ['label'=>'54', 'colorCode'=>'FB56B7', 'param'=>['province'=>'54'], 'description'=>'Total call_min Sistan'],
            ['label'=>'56', 'colorCode'=>'FB56B7', 'param'=>['province'=>'56'], 'description'=>'Total call_min Khorasan south'],
            ['label'=>'58', 'colorCode'=>'FB56B7', 'param'=>['province'=>'58'], 'description'=>'Total call_min Khorasan north'],
            ['label'=>'61', 'colorCode'=>'FB56B7', 'param'=>['province'=>'61'], 'description'=>'Total call_min Khozestan'],
            ['label'=>'66', 'colorCode'=>'FB56B7', 'param'=>['province'=>'66'], 'description'=>'Total call_min Lorestan'],
            ['label'=>'71', 'colorCode'=>'FB56B7', 'param'=>['province'=>'71'], 'description'=>'Total call_min Fars'],
            ['label'=>'74', 'colorCode'=>'FB56B7', 'param'=>['province'=>'74'], 'description'=>'Total call_min Kohkiloye'],
            ['label'=>'76', 'colorCode'=>'FB56B7', 'param'=>['province'=>'76'], 'description'=>'Total call_min Hormozgan'],
            ['label'=>'77', 'colorCode'=>'FB56B7', 'param'=>['province'=>'77'], 'description'=>'Total call_min Boshehr'],
            ['label'=>'81', 'colorCode'=>'FB56B7', 'param'=>['province'=>'81'], 'description'=>'Total call_min Hamedan'],
            ['label'=>'83', 'colorCode'=>'FB56B7', 'param'=>['province'=>'83'], 'description'=>'Total call_min Kermanshah'],
            ['label'=>'84', 'colorCode'=>'FB56B7', 'param'=>['province'=>'84'], 'description'=>'Total call_min Ilam'],
            ['label'=>'86', 'colorCode'=>'FB56B7', 'param'=>['province'=>'86'], 'description'=>'Total call_min Markazi'],
            ['label'=>'87', 'colorCode'=>'FB56B7', 'param'=>['province'=>'87'], 'description'=>'Total call_min Kordestan'],

        ];
    }
}
