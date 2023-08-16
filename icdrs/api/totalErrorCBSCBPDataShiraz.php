<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalErrorCBSCBPDataShiraz extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'Total Error Shiraz';

        $this->chartLengendHeightPerPixel = 100;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_cbp_data_err_code_log
                         WHERE cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         AND cerrc_id = ':cerrc_id:'
                         AND region_id = '3'
                         GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                         ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";
        
        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 24*60;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 100;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 13;

        $this->entities = [
            ['label'=>'E-118010145', 'colorCode'=>'D65DB1', 'param'=>['cerrc_id'=>'2'], 'description'=>'Session timeout'],
            ['label'=>'E-118013972', 'colorCode'=>'FF6F91', 'param'=>['cerrc_id'=>'3'], 'description'=>"There is no effective offering rating when the service is being used"],
            ['label'=>'E-118185802', 'colorCode'=>'FF9671', 'param'=>['cerrc_id'=>'4'], 'description'=>"No matched IP address is found"],
            ['label'=>'E-118013201', 'colorCode'=>'296073', 'param'=>['cerrc_id'=>'5'], 'description'=>'The subscriber may be in the Suspend state'],
            ['label'=>'E-118013901', 'colorCode'=>'FFC75F', 'param'=>['cerrc_id'=>'6'], 'description'=>'The fee calculated during rating does not cover all service amount'],
            ['label'=>'E-412021209', 'colorCode'=>'F9F871', 'param'=>['cerrc_id'=>'7'], 'description'=>'Unique key conflict.'],
            ['label'=>'E-118010202', 'colorCode'=>'008E9B', 'param'=>['cerrc_id'=>'8'], 'description'=>'Insufficient balance'],
            ['label'=>'E-118010106', 'colorCode'=>'008F7A', 'param'=>['cerrc_id'=>'9'], 'description'=>'Internal error nodata is found'],
            ['label'=>'E-412024119', 'colorCode'=>'B39CD0', 'param'=>['cerrc_id'=>'10'], 'description'=>'The client request is not responded in a specified period'],
            ['label'=>'E-118013903', 'colorCode'=>'4B4453', 'param'=>['cerrc_id'=>'11'], 'description'=>"The subscriber's primary offering is not found"],
            ['label'=>'E-118010110', 'colorCode'=>'B0A8B9', 'param'=>['cerrc_id'=>'12'], 'description'=>'Internal error: invalid data'],
            ['label'=>'E-412020474', 'colorCode'=>'C34A36', 'param'=>['cerrc_id'=>'13'], 'description'=>'Failed to lock the object due to timeout'],
            ['label'=>'E-118013013', 'colorCode'=>'FF8066', 'param'=>['cerrc_id'=>'14'], 'description'=>'Subscriber identification failed because the reported number unknown'],
            ['label'=>'E-118013974', 'colorCode'=>'F3C5FF', 'param'=>['cerrc_id'=>'18'], 'description'=>'The rank quantity in cyclical rank mode reach is maximum'],
            ['label'=>'E-412020479', 'colorCode'=>'936C00', 'param'=>['cerrc_id'=>'19'], 'description'=>'DBAgent failed to connect'],
            ['label'=>'E-118013029', 'colorCode'=>'FF00FF', 'param'=>['cerrc_id'=>'20'], 'description'=>'Failed to activate the subscriber'],
            ['label'=>'E-412020217', 'colorCode'=>'AD5E00', 'param'=>['cerrc_id'=>'21'], 'description'=>'Failed to obtain the connection for reading data.'],
            ['label'=>'E-118010161', 'colorCode'=>'AD0A00', 'param'=>['cerrc_id'=>'22'], 'description'=>'Customer-account-subscriber data is not found'],
            ['label'=>'E-118010102', 'colorCode'=>'AD3E00', 'param'=>['cerrc_id'=>'23'], 'description'=>'Internal error: Invalid parameter'],
            ['label'=>'E-412020216', 'colorCode'=>'A50E00', 'param'=>['cerrc_id'=>'24'], 'description'=>'Failed to obtain the connection for writing data'],
            ['label'=>'E-412020002', 'colorCode'=>'AD0E60', 'param'=>['cerrc_id'=>'25'], 'description'=>'No BO record or AVP found'],
            ['label'=>'E-118185532', 'colorCode'=>'AD0700', 'param'=>['cerrc_id'=>'26'], 'description'=>'unknown'],
            ['label'=>'E-412025016', 'colorCode'=>'AD9E00', 'param'=>['cerrc_id'=>'27'], 'description'=>'The driver detects that the data source is overloaded'],
            ['label'=>'E-412024105', 'colorCode'=>'AD0300', 'param'=>['cerrc_id'=>'28'], 'description'=>'unknown'],
            ['label'=>'E-20701', 'colorCode'=>'AD0100', 'param'=>['cerrc_id'=>'29'], 'description'=>'unknown'],
        ];
    }
}