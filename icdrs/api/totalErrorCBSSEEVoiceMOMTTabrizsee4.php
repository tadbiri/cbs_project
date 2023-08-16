<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalErrorCBSSEEVoiceMOMTTabrizsee4 extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'Total Error Tabriz SEE4';

        $this->chartLengendHeightPerPixel = 60;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_see_voice_err_code_log
                         WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         AND serrc_code = ':error_code:'
                         AND see_id = '10'
                         GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                         ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";
        
        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 24*60;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 30;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 3;

        $this->entities = [
            ['label'=>'E-0', 'colorCode'=>'FF0000', 'param'=>['error_code'=>'0'], 'description'=>'Other errors'],
            ['label'=>'E-99', 'colorCode'=>'FF4000', 'param'=>['error_code'=>'99'], 'description'=>'Other errors'],
            ['label'=>'E-1001', 'colorCode'=>'FFBF00', 'param'=>['error_code'=>'1001'], 'description'=>"The subscriber's balance is insufficient"],
            ['label'=>'E-1002', 'colorCode'=>'FFFF00', 'param'=>['error_code'=>'1002'], 'description'=>"The subscriber's account has expired"],
            ['label'=>'E-1003', 'colorCode'=>'BFFF00', 'param'=>['error_code'=>'1003'], 'description'=>'The subscriber has claimed missing'],
            ['label'=>'E-1004', 'colorCode'=>'80FF00', 'param'=>['error_code'=>'1004'], 'description'=>'The subscriber is not activated'],
            ['label'=>'E-1005', 'colorCode'=>'40FF00', 'param'=>['error_code'=>'1005'], 'description'=>'The subscriber does not exist'],
            ['label'=>'E-1006', 'colorCode'=>'00FF00', 'param'=>['error_code'=>'1006'], 'description'=>'The called party does not answer the call'],
            ['label'=>'E-1007', 'colorCode'=>'00FF40', 'param'=>['error_code'=>'1007'], 'description'=>'The called party is busy'],
            ['label'=>'E-1008', 'colorCode'=>'00FF80', 'param'=>['error_code'=>'1008'], 'description'=>'The calling party hangs up'],
            ['label'=>'E-1009', 'colorCode'=>'00FFBF', 'param'=>['error_code'=>'1009'], 'description'=>'The service is unavailable to subscribers'],
            ['label'=>'E-1010', 'colorCode'=>'00FFFF', 'param'=>['error_code'=>'1010'], 'description'=>'The subscriber is not enough after the call is connected'],
            ['label'=>'E-2001', 'colorCode'=>'00BFFF', 'param'=>['error_code'=>'2001'], 'description'=>'The number is barred'],
            ['label'=>'E-2002', 'colorCode'=>'0080FF', 'param'=>['error_code'=>'2002'], 'description'=>'Failed to encode or decode the DCC message'],
            ['label'=>'E-2003', 'colorCode'=>'0040FF', 'param'=>['error_code'=>'2003'], 'description'=>'System configuration error'],
            ['label'=>'E-2004', 'colorCode'=>'0000FF', 'param'=>['error_code'=>'2004'], 'description'=>'Failed to encode or decode the CAP signal'],
            ['label'=>'E-2005', 'colorCode'=>'4000FF', 'param'=>['error_code'=>'2005'], 'description'=>'A route selection error occurs'],
            ['label'=>'E-3001', 'colorCode'=>'8000FF', 'param'=>['error_code'=>'3001'], 'description'=>'The CCR{Initial} or CCR{Event} message timed out before the call is connected'],
            ['label'=>'E-3002', 'colorCode'=>'BF00FF', 'param'=>['error_code'=>'3002'], 'description'=>'Failed to access the database'],
            ['label'=>'E-3003', 'colorCode'=>'FF00FF', 'param'=>['error_code'=>'3003'], 'description'=>'A signaling exception occurred before the call is connected'],
            ['label'=>'E-3004', 'colorCode'=>'FF00BF', 'param'=>['error_code'=>'3004'], 'description'=>'The MSC delivers a TC-ABORT event to the OCG before the call is connected'],
            ['label'=>'E-3005', 'colorCode'=>'FF0080', 'param'=>['error_code'=>'3005'], 'description'=>'The MSC delivers a TC-U-ABORT event to the OCG before the call is connected'],
            ['label'=>'E-3006', 'colorCode'=>'FF0040', 'param'=>['error_code'=>'3006'], 'description'=>"The MSC delivers a TC-U-ERROR event to the OCG before the call is connected"],
            ['label'=>'E-3201', 'colorCode'=>'FF8040', 'param'=>['error_code'=>'3201'], 'description'=>'The CCR{Update} or CCR{Terminate} message timed out after the call is connected'],
            ['label'=>'E-3202', 'colorCode'=>'FFAA40', 'param'=>['error_code'=>'3202'], 'description'=>'A signaling exception occurred after the call is connected'],
        ];
    }
}