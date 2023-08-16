<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessRateCBSSEEVoiceMOMT extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'Success Rate Total';

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
                             FROM cbs_see_voice_err_code_log cl
                             WHERE cl.region_id = ':region_id:'
                             AND cl.subkey_code in(1,2,3,21,22,23,31,32,33,80,81,83)
                             AND SUBSTRING_INDEX(cl.cdr_date_time,'.',1) BETWEEN ':startTime:' AND ':endTime:'
                             GROUP BY SUBSTRING_INDEX(cl.cdr_date_time,':',2)
                             ORDER BY `dateTime` ASC;";
        
        $this->__sub__error = "SELECT SUBSTRING_INDEX(cl.cdr_date_time,':',2) AS 'dateTime', 
                             SUM(cl.e_count) AS 'errorCount'
                             FROM cbs_see_voice_err_code_log cl
                             WHERE cl.region_id = ':region_id:'
                             AND cl.subkey_code in(1,2,3,21,22,23,31,32,33,80,81,83)
                             AND cl.serrc_code IN (99,100,1001,1002,1003,1004,1005,1006,1007,1008,1009,1010,2001)
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

        $this->refreshLastPointBasedMinute = 30;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 3;

        $this->entities = [
            ['label'=>'Tehran', 'colorCode'=>'FB56B7', 'param'=>['region_id'=>'1'], 'description'=>'Tehran Total MO/MT/FW Success Rate'],
            ['label'=>'Tabriz', 'colorCode'=>'009900', 'param'=>['region_id'=>'2'], 'description'=>'Tabriz Total MO/MT/FW Success Rate'],
            ['label'=>'Shiraz', 'colorCode'=>'FEBF05', 'param'=>['region_id'=>'3'], 'description'=>'Shiraz Total MO/MT/FW Success Rate'],
            ['label'=>'Mashhad', 'colorCode'=>'008CFF', 'param'=>['region_id'=>'4'], 'description'=>'Mashhad Total MO/MT/FW Success Rate']
        ];
    }
}