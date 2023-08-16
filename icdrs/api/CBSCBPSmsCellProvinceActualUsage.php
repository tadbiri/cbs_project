<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart1.php";

class CBSCBPSmsCellProvinceActualUsage extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Province SMS Actual Usage Analysis';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "set statement_timeout= '600000s';
                        SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                        FROM cbs_cbp_sms_cell_log
                        WHERE city_code = ':province:'
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
            ['label'=>'21', 'colorCode'=>'FFBF00', 'param'=>['province'=>'21'], 'description'=>'Total actual_usage tehran'],
            ['label'=>'31', 'colorCode'=>'FFFF00', 'param'=>['province'=>'31'], 'description'=>'Total actual_usage Esfahan'],
            ['label'=>'11', 'colorCode'=>'BFFF00', 'param'=>['province'=>'11'], 'description'=>'Total actual_usage Mazandaran'],
            ['label'=>'13', 'colorCode'=>'80FF00', 'param'=>['province'=>'13'], 'description'=>'Total actual_usage Gilan'],
            ['label'=>'17', 'colorCode'=>'40FF00', 'param'=>['province'=>'17'], 'description'=>'Total actual_usage Golestan'],
            ['label'=>'23', 'colorCode'=>'00FF00', 'param'=>['province'=>'23'], 'description'=>'Total actual_usage Semnan'],
            ['label'=>'24', 'colorCode'=>'00FF40', 'param'=>['province'=>'24'], 'description'=>'Total actual_usage Zanjan'],
            ['label'=>'25', 'colorCode'=>'00FF80', 'param'=>['province'=>'25'], 'description'=>'Total actual_usage Qom'],
            ['label'=>'26', 'colorCode'=>'00FFBF', 'param'=>['province'=>'26'], 'description'=>'Total actual_usage Alborz'],
            ['label'=>'28', 'colorCode'=>'00FFFF', 'param'=>['province'=>'28'], 'description'=>'Total actual_usage Qazvin'],
            ['label'=>'34', 'colorCode'=>'00BFFF', 'param'=>['province'=>'34'], 'description'=>'Total actual_usage Kerman'],
            ['label'=>'35', 'colorCode'=>'0080FF', 'param'=>['province'=>'35'], 'description'=>'Total actual_usage Yazd'],
            ['label'=>'38', 'colorCode'=>'0040FF', 'param'=>['province'=>'38'], 'description'=>'Total actual_usage Charmahal'],
            ['label'=>'41', 'colorCode'=>'0000FF', 'param'=>['province'=>'41'], 'description'=>'Total actual_usage azarbayejan east'],
            ['label'=>'44', 'colorCode'=>'4000FF', 'param'=>['province'=>'44'], 'description'=>'Total actual_usage Azarbayejan west'],
            ['label'=>'45', 'colorCode'=>'8000FF', 'param'=>['province'=>'45'], 'description'=>'Total actual_usage Ardabil'],
            ['label'=>'51', 'colorCode'=>'BF00FF', 'param'=>['province'=>'51'], 'description'=>'Total actual_usage Mashhhad'],
            ['label'=>'54', 'colorCode'=>'FF00FF', 'param'=>['province'=>'54'], 'description'=>'Total actual_usage Sistan'],
            ['label'=>'56', 'colorCode'=>'FF00BF', 'param'=>['province'=>'56'], 'description'=>'Total actual_usage Khorasan south'],
            ['label'=>'58', 'colorCode'=>'FF0080', 'param'=>['province'=>'58'], 'description'=>'Total actual_usage Khorasan north'],
            ['label'=>'61', 'colorCode'=>'FF0040', 'param'=>['province'=>'61'], 'description'=>'Total actual_usage Khozestan'],
            ['label'=>'66', 'colorCode'=>'FF8040', 'param'=>['province'=>'66'], 'description'=>'Total actual_usage Lorestan'],
            ['label'=>'71', 'colorCode'=>'FFAA40', 'param'=>['province'=>'71'], 'description'=>'Total actual_usage Fars'],
            ['label'=>'74', 'colorCode'=>'FB56B7', 'param'=>['province'=>'74'], 'description'=>'Total actual_usage Kohkiloye'],
            ['label'=>'76', 'colorCode'=>'06F3FF', 'param'=>['province'=>'76'], 'description'=>'Total actual_usage Hormozgan'],
            ['label'=>'77', 'colorCode'=>'06B3FE', 'param'=>['province'=>'77'], 'description'=>'Total actual_usage Boshehr'],
            ['label'=>'81', 'colorCode'=>'0683FA', 'param'=>['province'=>'81'], 'description'=>'Total actual_usage Hamedan'],
            ['label'=>'83', 'colorCode'=>'0643FB', 'param'=>['province'=>'83'], 'description'=>'Total actual_usage Kermanshah'],
            ['label'=>'84', 'colorCode'=>'0603FC', 'param'=>['province'=>'84'], 'description'=>'Total actual_usage Ilam'],
            ['label'=>'86', 'colorCode'=>'4603FD', 'param'=>['province'=>'86'], 'description'=>'Total actual_usage Markazi'],
            ['label'=>'87', 'colorCode'=>'86034F', 'param'=>['province'=>'87'], 'description'=>'Total actual_usage Kordestan'],

        ];
    }
}
