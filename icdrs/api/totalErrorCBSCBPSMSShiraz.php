<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalErrorCBSCBPSMSShiraz extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'Total Error Shiraz';

        $this->chartLengendHeightPerPixel = 55;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_cbp_sms_err_code_log
                         WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         AND err_code = ':err_code:'
                         AND region_id = '3'
                         GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                         ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";
        
        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 24*60;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 100;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 12;

        $this->entities = [
            ['label'=>'E-118010145', 'colorCode'=>'D65DB1', 'param'=>['err_code'=>'118010145'], 'description'=>'Session timeout'],
            ['label'=>'E-118013972', 'colorCode'=>'FF6F91', 'param'=>['err_code'=>'118013972'], 'description'=>"There is no effective offering rating when the service is being used"],
            ['label'=>'E-118185802', 'colorCode'=>'FF9671', 'param'=>['err_code'=>'118185802'], 'description'=>"No matched IP address is found"],
            ['label'=>'E-118013201', 'colorCode'=>'296073', 'param'=>['err_code'=>'118013201'], 'description'=>'The subscriber may be in the Suspend state'],
            ['label'=>'E-118013901', 'colorCode'=>'FFC75F', 'param'=>['err_code'=>'118013901'], 'description'=>'The fee calculated during rating does not cover all service amount'],
            ['label'=>'E-412021209', 'colorCode'=>'F9F871', 'param'=>['err_code'=>'412021209'], 'description'=>'Unique key conflict.'],
            ['label'=>'E-118010202', 'colorCode'=>'008E9B', 'param'=>['err_code'=>'118010202'], 'description'=>'Insufficient balance'],
            ['label'=>'E-118010106', 'colorCode'=>'008F7A', 'param'=>['err_code'=>'118010106'], 'description'=>'Internal error nodata is found'],
            ['label'=>'E-412024119', 'colorCode'=>'B39CD0', 'param'=>['err_code'=>'412024119'], 'description'=>'The client request is not responded in a specified period'],
            ['label'=>'E-118013903', 'colorCode'=>'4B4453', 'param'=>['err_code'=>'118013903'], 'description'=>"The subscriber's primary offering is not found"],
            ['label'=>'E-118010110', 'colorCode'=>'B0A8B9', 'param'=>['err_code'=>'118010110'], 'description'=>'Internal error: invalid data'],
            ['label'=>'E-412020474', 'colorCode'=>'C34A36', 'param'=>['err_code'=>'412020474'], 'description'=>'Failed to lock the object due to timeout'],
            ['label'=>'E-118013013', 'colorCode'=>'FF8066', 'param'=>['err_code'=>'118013013'], 'description'=>'Subscriber identification failed because the reported number unknown'],
            ['label'=>'E-118013974', 'colorCode'=>'F3C5FF', 'param'=>['err_code'=>'118013974'], 'description'=>'The rank quantity in cyclical rank mode reach is maximum'],
            ['label'=>'E-412020479', 'colorCode'=>'936C00', 'param'=>['err_code'=>'412020479'], 'description'=>'DBAgent failed to connect'],
            ['label'=>'E-118013029', 'colorCode'=>'FF00FF', 'param'=>['err_code'=>'118013029'], 'description'=>'Failed to activate the subscriber'],
            ['label'=>'E-412020217', 'colorCode'=>'AD5E00', 'param'=>['err_code'=>'412020217'], 'description'=>'Failed to obtain the connection for reading data.'],
            ['label'=>'E-118010161', 'colorCode'=>'AD0E00', 'param'=>['err_code'=>'118010161'], 'description'=>'Customer-account-subscriber data is not found'],
            ['label'=>'E-118013106', 'colorCode'=>'FD0F00', 'param'=>['err_code'=>'118013106'], 'description'=>'No international number head is found in the International Number Head table.'],
            ['label'=>'E-118013105', 'colorCode'=>'BD0E10', 'param'=>['err_code'=>'118013105'], 'description'=>'No national number head is found in the National Number Head table.'],
            ['label'=>'E-118185512', 'colorCode'=>'CA0E20', 'param'=>['err_code'=>'118185512'], 'description'=>'The refund log record cannot be found.'],
            ['label'=>'E-412220403', 'colorCode'=>'FF00dF', 'param'=>['err_code'=>'412220403'], 'description'=>'gmdb error'],
            ['label'=>'E-412015410', 'colorCode'=>'FF8056', 'param'=>['err_code'=>'412015410'], 'description'=>'Failed to execute the Native function.'],
            ['label'=>'E-412020216', 'colorCode'=>'008F7A', 'param'=>['err_code'=>'412020216'], 'description'=>'Failed to obtain the connection for writing data.'],
            ['label'=>'E-412020026', 'colorCode'=>'CA7E00', 'param'=>['err_code'=>'412020026'], 'description'=>'Failed to obtain resources.']
        ];
    }
}