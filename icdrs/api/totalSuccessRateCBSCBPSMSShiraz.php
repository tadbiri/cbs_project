<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessRateCBSCBPSMSShiraz extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'Success Rate Shiraz';

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
         * - For define final shape of chart SMS, implement '__sub__function' function.
         *      In this method all defined sub queries result available by related pattern variable name (eg: $this->__users)
         * 
         * 
         * 
         */
        $this->__sub__total = "SELECT SUBSTRING_INDEX(cl.cdr_date_time,':',2) AS 'dateTime', 
                             SUM(cl.e_count) AS 'errorCount'
                             FROM cbs_cbp_sms_err_code_log cl
                             WHERE cl.cbp_id = ':cbp_id:'
                             AND SUBSTRING_INDEX(cl.cdr_date_time,'.',1) BETWEEN ':startTime:' AND ':endTime:'
                             GROUP BY SUBSTRING_INDEX(cl.cdr_date_time,':',2)
                             ORDER BY `dateTime` ASC;";
        
        $this->__sub__error = "SELECT SUBSTRING_INDEX(cl.cdr_date_time,':',2) AS 'dateTime', 
                             SUM(cl.e_count) AS 'errorCount'
                             FROM cbs_cbp_sms_err_code_log cl
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

        $this->chartMinuteShift = 12;

        $this->entities = [
            ['label'=>'scbp1', 'colorCode'=>'FFBF00', 'param'=>['cbp_id'=>'41'],'description'=>'Shiraz CBP1 Total SMS Success'],
            ['label'=>'scbp2', 'colorCode'=>'FFFF00', 'param'=>['cbp_id'=>'42'],'description'=>'Shiraz CBP2 Total SMS Success'],
            ['label'=>'scbp3', 'colorCode'=>'BFFF00', 'param'=>['cbp_id'=>'43'],'description'=>'Shiraz CBP3 Total SMS Success'],
            ['label'=>'scbp4', 'colorCode'=>'80FF00', 'param'=>['cbp_id'=>'44'],'description'=>'Shiraz CBP4 Total SMS Success'],
            ['label'=>'scbp5', 'colorCode'=>'40FF00', 'param'=>['cbp_id'=>'45'],'description'=>'Shiraz CBP5 Total SMS Success'],
            ['label'=>'scbp6', 'colorCode'=>'00FF00', 'param'=>['cbp_id'=>'46'],'description'=>'Shiraz CBP6 Total SMS Success'],
            ['label'=>'scbp7', 'colorCode'=>'00FF40', 'param'=>['cbp_id'=>'47'],'description'=>'Shiraz CBP7 Total SMS Success'],
            ['label'=>'scbp8', 'colorCode'=>'00FF80', 'param'=>['cbp_id'=>'48'],'description'=>'Shiraz CBP8 Total SMS Success'],
            ['label'=>'scbp9', 'colorCode'=>'00FFBF', 'param'=>['cbp_id'=>'49'],'description'=>'Shiraz CBP9 Total SMS Success'],
            ['label'=>'scbp10', 'colorCode'=>'00FFFF', 'param'=>['cbp_id'=>'50'],'description'=>'Shiraz CBP10 Total SMS Success'],
            ['label'=>'scbp11', 'colorCode'=>'00BFFF', 'param'=>['cbp_id'=>'51'],'description'=>'Shiraz CBP11 Total SMS Success'],
            ['label'=>'scbp12', 'colorCode'=>'0080FF', 'param'=>['cbp_id'=>'52'],'description'=>'Shiraz CBP12 Total SMS Success'],
            ['label'=>'scbp13', 'colorCode'=>'0040FF', 'param'=>['cbp_id'=>'53'],'description'=>'Shiraz CBP13 Total SMS Success'],
            ['label'=>'scbp14', 'colorCode'=>'0000FF', 'param'=>['cbp_id'=>'54'],'description'=>'Shiraz CBP14 Total SMS Success'],
            ['label'=>'scbp15', 'colorCode'=>'4000FF', 'param'=>['cbp_id'=>'55'],'description'=>'Shiraz CBP15 Total SMS Success'],
            ['label'=>'scbp16', 'colorCode'=>'8000FF', 'param'=>['cbp_id'=>'56'],'description'=>'Shiraz CBP16 Total SMS Success']
        ];
    }
}