<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart1.php";

class CBSCBPVoiceCellProvinceCallMinIMS extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Province Call Min IMS';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "set statement_timeout= '600000s';
                        SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(call_min)/60 AS \"errorCount\"
                        FROM cbs_cbp_rec_cell_log
                        WHERE area_code = ':province:'   
                        And cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                        And cell_code like '%i%'
                        GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                        ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";

        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 60*24;

        $this->periodForFirstBuildCacheBasedMinute = 10*24*60;

        $this->refreshLastPointBasedMinute = 60;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 0;

        $this->deleteCoefficient = 0.2;

        $this->entities = [
            ['label'=>'21', 'colorCode'=>'FB56B7', 'param'=>['province'=>'21'], 'description'=>'Total call_min IMS tehran'],
            ['label'=>'31', 'colorCode'=>'FB56B7', 'param'=>['province'=>'31'], 'description'=>'Total call_min IMS Esfahan'],
            ['label'=>'11', 'colorCode'=>'FB56B7', 'param'=>['province'=>'11'], 'description'=>'Total call_min IMS Mazandaran'],
            ['label'=>'13', 'colorCode'=>'FB56B7', 'param'=>['province'=>'13'], 'description'=>'Total call_min IMS Gilan'],
            ['label'=>'17', 'colorCode'=>'FB56B7', 'param'=>['province'=>'17'], 'description'=>'Total call_min IMS Golestan'],
            ['label'=>'23', 'colorCode'=>'FB56B7', 'param'=>['province'=>'23'], 'description'=>'Total call_min IMS Semnan'],
            ['label'=>'24', 'colorCode'=>'FB56B7', 'param'=>['province'=>'24'], 'description'=>'Total call_min IMS Zanjan'],
            ['label'=>'25', 'colorCode'=>'FB56B7', 'param'=>['province'=>'25'], 'description'=>'Total call_min IMS Qom'],
            ['label'=>'26', 'colorCode'=>'FB56B7', 'param'=>['province'=>'26'], 'description'=>'Total call_min IMS Alborz'],
            ['label'=>'28', 'colorCode'=>'FB56B7', 'param'=>['province'=>'28'], 'description'=>'Total call_min IMS Qazvin'],
            ['label'=>'34', 'colorCode'=>'FB56B7', 'param'=>['province'=>'34'], 'description'=>'Total call_min IMS Kerman'],
            ['label'=>'35', 'colorCode'=>'FB56B7', 'param'=>['province'=>'35'], 'description'=>'Total call_min IMS Yazd'],
            ['label'=>'38', 'colorCode'=>'FB56B7', 'param'=>['province'=>'38'], 'description'=>'Total call_min IMS Charmahal'],
            ['label'=>'41', 'colorCode'=>'FB56B7', 'param'=>['province'=>'41'], 'description'=>'Total call_min IMS azarbayejan east'],
            ['label'=>'44', 'colorCode'=>'FB56B7', 'param'=>['province'=>'44'], 'description'=>'Total call_min IMS Azarbayejan west'],
            ['label'=>'45', 'colorCode'=>'FB56B7', 'param'=>['province'=>'45'], 'description'=>'Total call_min IMS Ardabil'],
            ['label'=>'51', 'colorCode'=>'FB56B7', 'param'=>['province'=>'51'], 'description'=>'Total call_min IMS Mashhhad'],
            ['label'=>'54', 'colorCode'=>'FB56B7', 'param'=>['province'=>'54'], 'description'=>'Total call_min IMS Sistan'],
            ['label'=>'56', 'colorCode'=>'FB56B7', 'param'=>['province'=>'56'], 'description'=>'Total call_min IMS Khorasan south'],
            ['label'=>'58', 'colorCode'=>'FB56B7', 'param'=>['province'=>'58'], 'description'=>'Total call_min IMS Khorasan north'],
            ['label'=>'61', 'colorCode'=>'FB56B7', 'param'=>['province'=>'61'], 'description'=>'Total call_min IMS Khozestan'],
            ['label'=>'66', 'colorCode'=>'FB56B7', 'param'=>['province'=>'66'], 'description'=>'Total call_min IMS Lorestan'],
            ['label'=>'71', 'colorCode'=>'FB56B7', 'param'=>['province'=>'71'], 'description'=>'Total call_min IMS Fars'],
            ['label'=>'74', 'colorCode'=>'FB56B7', 'param'=>['province'=>'74'], 'description'=>'Total call_min IMS Kohkiloye'],
            ['label'=>'76', 'colorCode'=>'FB56B7', 'param'=>['province'=>'76'], 'description'=>'Total call_min IMS Hormozgan'],
            ['label'=>'77', 'colorCode'=>'FB56B7', 'param'=>['province'=>'77'], 'description'=>'Total call_min IMS Boshehr'],
            ['label'=>'81', 'colorCode'=>'FB56B7', 'param'=>['province'=>'81'], 'description'=>'Total call_min IMS Hamedan'],
            ['label'=>'83', 'colorCode'=>'FB56B7', 'param'=>['province'=>'83'], 'description'=>'Total call_min IMS Kermanshah'],
            ['label'=>'84', 'colorCode'=>'FB56B7', 'param'=>['province'=>'84'], 'description'=>'Total call_min IMS Ilam'],
            ['label'=>'86', 'colorCode'=>'FB56B7', 'param'=>['province'=>'86'], 'description'=>'Total call_min IMS Markazi'],
            ['label'=>'87', 'colorCode'=>'FB56B7', 'param'=>['province'=>'87'], 'description'=>'Total call_min IMS Kordestan'],

        ];
    }
}
