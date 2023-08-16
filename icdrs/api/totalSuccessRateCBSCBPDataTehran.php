<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessRateCBSCBPDataTehran extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'Success Rate Tehran';

        $this->chartLengendHeightPerPixel = 40;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        // In this Case (SUB_QUERY_MODE), query must be be empty to detect mode.
        $this->query = "";

        /**
         * A sub query defined in bellow type.
         * For example for a query that it name is 'users'
         * set in pattern -> $this->__sub__users
         * results of this query avaiable in $this->__users.
         * 
         * Mandatory Settings:
         * - All query that defined in this type, must be set in '__sub__prepair' array.
         * 
         * - For define final shape of chart data, implement '__sub__function' function.
         *      In this method all defined sub queries result available by related pattern variable name (eg: $this->__users)
         * 
         * 
         * 
         */
        $this->__sub__total = "SELECT SUBSTRING_INDEX(cl.cdr_date_time,':',2) AS 'dateTime', 
                             SUM(cl.e_count) AS 'errorCount'
                             FROM cbs_cbp_data_err_code_log cl
                             WHERE cl.cbp_id = ':cbp_id:'
                             AND SUBSTRING_INDEX(cl.cdr_date_time,'.',1) BETWEEN ':startTime:' AND ':endTime:'
                             GROUP BY SUBSTRING_INDEX(cl.cdr_date_time,':',2)
                             ORDER BY `dateTime` ASC;";
        
        $this->__sub__error = "SELECT SUBSTRING_INDEX(cl.cdr_date_time,':',2) AS 'dateTime', 
                             SUM(cl.e_count) AS 'errorCount'
                             FROM cbs_cbp_data_err_code_log cl
                             WHERE cl.cbp_id = ':cbp_id:'
                             AND cl.cerrc_id IN (1,2,3,5,6,8,20)
                             AND SUBSTRING_INDEX(cl.cdr_date_time,'.',1) BETWEEN ':startTime:' AND ':endTime:'
                             GROUP BY SUBSTRING_INDEX(cl.cdr_date_time,':',2)
                             ORDER BY `dateTime` ASC;";


        /**
         * Prepair all sub queries.
         */
        $this->__sub__prepair = ['total', 'error'];

        /**
         * Implement final shape of chart.
         */
        $this->__sub__function = function(){
            $result = [];
            // Iterate on total result.
            foreach($this->__total as $t){
                $t_dateTime = $t['dateTime'];
                // Find alongside dateTime to get errorCode in __error.
                $index = TypeConvertorHelper::getIndexOfKeyValueInArray($this->__error, ['dateTime', $t_dateTime]);
                $t_errorCount = (float) $t['errorCount'];
                $e_errorCount = (float) $this->__error[$index]['errorCount'];
                // Calculate average and store it on result list.
                //$errorCount = round(($e_errorCount/$t_errorCount), 2)*100;
                $errorCount = (float) ($e_errorCount/$t_errorCount)*100;
                $errorCount = number_format((float)$errorCount, 2, '.', '');
                $result[] = [
                    'dateTime'=>$t_dateTime,
                    'errorCount'=>$errorCount,
                ];
            }
            return $result;
        };

        
        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 24*60;

        $this->periodForFirstBuildCacheBasedMinute = 3*24*60;

        $this->refreshLastPointBasedMinute = 60;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 7;

        $this->entities = [
            ['label'=>'cbp1', 'colorCode'=>'FFBF00', 'param'=>['cbp_id'=>'1'],'description'=>'Tehran CBP1 Success Rate'],
            ['label'=>'cbp2', 'colorCode'=>'FFFF00', 'param'=>['cbp_id'=>'2'],'description'=>'Tehran CBP2 Success Rate'],
            ['label'=>'cbp3', 'colorCode'=>'BFFF00', 'param'=>['cbp_id'=>'3'],'description'=>'Tehran CBP3 Success Rate'],
            ['label'=>'cbp4', 'colorCode'=>'80FF00', 'param'=>['cbp_id'=>'4'],'description'=>'Tehran CBP4 Success Rate'],
            ['label'=>'cbp5', 'colorCode'=>'40FF00', 'param'=>['cbp_id'=>'5'],'description'=>'Tehran CBP5 Success Rate'],
            ['label'=>'cbp6', 'colorCode'=>'00FF00', 'param'=>['cbp_id'=>'6'],'description'=>'Tehran CBP6 Success Rate'],
            ['label'=>'cbp7', 'colorCode'=>'00FF40', 'param'=>['cbp_id'=>'7'],'description'=>'Tehran CBP7 Success Rate'],
            ['label'=>'cbp8', 'colorCode'=>'00FF80', 'param'=>['cbp_id'=>'8'],'description'=>'Tehran CBP8 Success Rate'],
            ['label'=>'cbp9', 'colorCode'=>'00FFBF', 'param'=>['cbp_id'=>'9'],'description'=>'Tehran CBP9 Success Rate'],
            ['label'=>'cbp10', 'colorCode'=>'00FFFF', 'param'=>['cbp_id'=>'10'],'description'=>'Tehran CBP10 Success Rate'],
            ['label'=>'cbp11', 'colorCode'=>'00BFFF', 'param'=>['cbp_id'=>'11'],'description'=>'Tehran CBP11 Success Rate'],
            ['label'=>'cbp12', 'colorCode'=>'0080FF', 'param'=>['cbp_id'=>'12'],'description'=>'Tehran CBP12 Success Rate'],
            ['label'=>'cbp13', 'colorCode'=>'0040FF', 'param'=>['cbp_id'=>'13'],'description'=>'Tehran CBP13 Success Rate'],
            ['label'=>'cbp14', 'colorCode'=>'0000FF', 'param'=>['cbp_id'=>'14'],'description'=>'Tehran CBP14 Success Rate'],
            ['label'=>'cbp15', 'colorCode'=>'4000FF', 'param'=>['cbp_id'=>'15'],'description'=>'Tehran CBP15 Success Rate'],
            ['label'=>'cbp16', 'colorCode'=>'8000FF', 'param'=>['cbp_id'=>'16'],'description'=>'Tehran CBP16 Success Rate'],
            ['label'=>'cbp17', 'colorCode'=>'BF00FF', 'param'=>['cbp_id'=>'17'],'description'=>'Tehran CBP17 Success Rate'],
            ['label'=>'cbp18', 'colorCode'=>'FF00FF', 'param'=>['cbp_id'=>'18'],'description'=>'Tehran CBP18 Success Rate'],
            ['label'=>'cbp19', 'colorCode'=>'FF00BF', 'param'=>['cbp_id'=>'19'],'description'=>'Tehran CBP19 Success Rate'],
            ['label'=>'cbp20', 'colorCode'=>'FF0080', 'param'=>['cbp_id'=>'20'],'description'=>'Tehran CBP20 Success Rate']
        ];
    }
}