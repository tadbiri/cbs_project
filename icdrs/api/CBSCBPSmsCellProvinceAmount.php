<?php

// Load Total Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart1.php";

class CBSCBPSmsCellProvinceAmount extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);
        
        $this->chartTitle = 'Total Province SMS Amount';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "set statement_timeout= '600000s';
                        SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", (sum(debit_amount)/10)+(sum(free_amount)/10) AS \"errorCount\"
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
            ['label'=>'21', 'colorCode'=>'FFBF00', 'param'=>['province'=>'21'], 'description'=>'Total debit_amount tehran'],
            ['label'=>'31', 'colorCode'=>'FFFF00', 'param'=>['province'=>'31'], 'description'=>'Total debit_amount Esfahan'],
            ['label'=>'11', 'colorCode'=>'BFFF00', 'param'=>['province'=>'11'], 'description'=>'Total debit_amount Mazandaran'],
            ['label'=>'13', 'colorCode'=>'80FF00', 'param'=>['province'=>'13'], 'description'=>'Total debit_amount Gilan'],
            ['label'=>'17', 'colorCode'=>'40FF00', 'param'=>['province'=>'17'], 'description'=>'Total debit_amount Golestan'],
            ['label'=>'23', 'colorCode'=>'00FF00', 'param'=>['province'=>'23'], 'description'=>'Total debit_amount Semnan'],
            ['label'=>'24', 'colorCode'=>'00FF40', 'param'=>['province'=>'24'], 'description'=>'Total debit_amount Zanjan'],
            ['label'=>'25', 'colorCode'=>'00FF80', 'param'=>['province'=>'25'], 'description'=>'Total debit_amount Qom'],
            ['label'=>'26', 'colorCode'=>'00FFBF', 'param'=>['province'=>'26'], 'description'=>'Total debit_amount Alborz'],
            ['label'=>'28', 'colorCode'=>'00FFFF', 'param'=>['province'=>'28'], 'description'=>'Total debit_amount Qazvin'],
            ['label'=>'34', 'colorCode'=>'00BFFF', 'param'=>['province'=>'34'], 'description'=>'Total debit_amount Kerman'],
            ['label'=>'35', 'colorCode'=>'0080FF', 'param'=>['province'=>'35'], 'description'=>'Total debit_amount Yazd'],
            ['label'=>'38', 'colorCode'=>'0040FF', 'param'=>['province'=>'38'], 'description'=>'Total debit_amount Charmahal'],
            ['label'=>'41', 'colorCode'=>'0000FF', 'param'=>['province'=>'41'], 'description'=>'Total debit_amount azarbayejan east'],
            ['label'=>'44', 'colorCode'=>'4000FF', 'param'=>['province'=>'44'], 'description'=>'Total debit_amount Azarbayejan west'],
            ['label'=>'45', 'colorCode'=>'8000FF', 'param'=>['province'=>'45'], 'description'=>'Total debit_amount Ardabil'],
            ['label'=>'51', 'colorCode'=>'BF00FF', 'param'=>['province'=>'51'], 'description'=>'Total debit_amount Mashhhad'],
            ['label'=>'54', 'colorCode'=>'FF00FF', 'param'=>['province'=>'54'], 'description'=>'Total debit_amount Sistan'],
            ['label'=>'56', 'colorCode'=>'FF00BF', 'param'=>['province'=>'56'], 'description'=>'Total debit_amount Khorasan south'],
            ['label'=>'58', 'colorCode'=>'FF0080', 'param'=>['province'=>'58'], 'description'=>'Total debit_amount Khorasan north'],
            ['label'=>'61', 'colorCode'=>'FF0040', 'param'=>['province'=>'61'], 'description'=>'Total debit_amount Khozestan'],
            ['label'=>'66', 'colorCode'=>'FF8040', 'param'=>['province'=>'66'], 'description'=>'Total debit_amount Lorestan'],
            ['label'=>'71', 'colorCode'=>'FFAA40', 'param'=>['province'=>'71'], 'description'=>'Total debit_amount Fars'],
            ['label'=>'74', 'colorCode'=>'FB56B7', 'param'=>['province'=>'74'], 'description'=>'Total debit_amount Kohkiloye'],
            ['label'=>'76', 'colorCode'=>'06F3FF', 'param'=>['province'=>'76'], 'description'=>'Total debit_amount Hormozgan'],
            ['label'=>'77', 'colorCode'=>'06B3FE', 'param'=>['province'=>'77'], 'description'=>'Total debit_amount Boshehr'],
            ['label'=>'81', 'colorCode'=>'0683FA', 'param'=>['province'=>'81'], 'description'=>'Total debit_amount Hamedan'],
            ['label'=>'83', 'colorCode'=>'0643FB', 'param'=>['province'=>'83'], 'description'=>'Total debit_amount Kermanshah'],
            ['label'=>'84', 'colorCode'=>'0603FC', 'param'=>['province'=>'84'], 'description'=>'Total debit_amount Ilam'],
            ['label'=>'86', 'colorCode'=>'4603FD', 'param'=>['province'=>'86'], 'description'=>'Total debit_amount Markazi'],
            ['label'=>'87', 'colorCode'=>'86034F', 'param'=>['province'=>'87'], 'description'=>'Total debit_amount Kordestan'],

        ];
    }
}
