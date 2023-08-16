<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalSuccessRateCBSCBPSMSTabriz extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'Success Rate Tabriz';

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
                             AND cl.cerrc_id IN (1,5,8,23,24)
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
            ['label'=>'tcbp1', 'colorCode'=>'FFBF00', 'param'=>['cbp_id'=>'21'],'description'=>'Tabriz CBP1 Success Rate'],
            ['label'=>'tcbp2', 'colorCode'=>'FFFF00', 'param'=>['cbp_id'=>'22'],'description'=>'Tabriz CBP2 Success Rate'],
            ['label'=>'tcbp3', 'colorCode'=>'BFFF00', 'param'=>['cbp_id'=>'23'],'description'=>'Tabriz CBP3 Success Rate'],
            ['label'=>'tcbp4', 'colorCode'=>'80FF00', 'param'=>['cbp_id'=>'24'],'description'=>'Tabriz CBP4 Success Rate'],
            ['label'=>'tcbp5', 'colorCode'=>'40FF00', 'param'=>['cbp_id'=>'25'],'description'=>'Tabriz CBP5 Success Rate'],
            ['label'=>'tcbp6', 'colorCode'=>'00FF00', 'param'=>['cbp_id'=>'26'],'description'=>'Tabriz CBP6 Success Rate'],
            ['label'=>'tcbp7', 'colorCode'=>'00FF40', 'param'=>['cbp_id'=>'27'],'description'=>'Tabriz CBP7 Success Rate'],
            ['label'=>'tcbp8', 'colorCode'=>'00FF80', 'param'=>['cbp_id'=>'28'],'description'=>'Tabriz CBP8 Success Rate'],
            ['label'=>'tcbp9', 'colorCode'=>'00FFBF', 'param'=>['cbp_id'=>'29'],'description'=>'Tabriz CBP9 Success Rate'],
            ['label'=>'tcbp10', 'colorCode'=>'00FFFF', 'param'=>['cbp_id'=>'30'],'description'=>'Tabriz CBP10 Success Rate'],
            ['label'=>'tcbp11', 'colorCode'=>'00BFFF', 'param'=>['cbp_id'=>'31'],'description'=>'Tabriz CBP11 Success Rate'],
            ['label'=>'tcbp12', 'colorCode'=>'0080FF', 'param'=>['cbp_id'=>'32'],'description'=>'Tabriz CBP12 Success Rate'],
            ['label'=>'tcbp13', 'colorCode'=>'0040FF', 'param'=>['cbp_id'=>'33'],'description'=>'Tabriz CBP13 Success Rate'],
            ['label'=>'tcbp14', 'colorCode'=>'0000FF', 'param'=>['cbp_id'=>'34'],'description'=>'Tabriz CBP14 Success Rate'],
            ['label'=>'tcbp15', 'colorCode'=>'4000FF', 'param'=>['cbp_id'=>'35'],'description'=>'Tabriz CBP15 Success Rate'],
            ['label'=>'tcbp16', 'colorCode'=>'8000FF', 'param'=>['cbp_id'=>'36'],'description'=>'Tabriz CBP16 Success Rate']
        ];
    }
}