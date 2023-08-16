<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalErrorCBSSEERecMSCNokia extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'Nokia MSC Success';

        $this->chartLengendHeightPerPixel = 55;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_see_rec_msc_log
                         WHERE termination_reason_id = '1' 
                         AND msc_id = ':msc_id:' 
                         AND cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                         ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";
        
        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 24*60;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 100;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 5;

        $this->entities = [
            ['label'=>'BandarAbas A', 'colorCode'=>'00713B', 'param'=>['msc_id'=>'6'],  'description'=>'MSBAA 989180910'],
            ['label'=>'BandarAbas B', 'colorCode'=>'006430', 'param'=>['msc_id'=>'7'],  'description'=>'MSBAB 989110770'],
            ['label'=>'Bojnord A', 'colorCode'=>'FF5B04', 'param'=>['msc_id'=>'10'], 'description'=>'MSBJA 989180760'],
            ['label'=>'Boshehr A', 'colorCode'=>'D73800', 'param'=>['msc_id'=>'11'], 'description'=>'MSBOA 989180750'],
            ['label'=>'Birjand A', 'colorCode'=>'DE3E00', 'param'=>['msc_id'=>'12'], 'description'=>'MSBRA 989180900'],
            ['label'=>'Esfehan A', 'colorCode'=>'E9FF00', 'param'=>['msc_id'=>'13'], 'description'=>'MSEFA 989180300'],
            ['label'=>'Esfehan B', 'colorCode'=>'AFC900', 'param'=>['msc_id'=>'14'], 'description'=>'MSEFB 989180310'],
            ['label'=>'Esfehan C', 'colorCode'=>'779500', 'param'=>['msc_id'=>'15'], 'description'=>'MSEFC 989180320'],
            ['label'=>'Esfehan D', 'colorCode'=>'456500', 'param'=>['msc_id'=>'16'], 'description'=>'MSEFD 989110320'],
            ['label'=>'Kerman A', 'colorCode'=>'94F1B1', 'param'=>['msc_id'=>'22'], 'description'=>'MSKRA 989110310'],
            ['label'=>'Kerman B', 'colorCode'=>'74D092', 'param'=>['msc_id'=>'23'], 'description'=>'MSKRB 989110340'],
            ['label'=>'Mashhad A', 'colorCode'=>'960000', 'param'=>['msc_id'=>'27'], 'description'=>'MSMDA 989110200'],
            ['label'=>'Mashhad B', 'colorCode'=>'B80000', 'param'=>['msc_id'=>'28'], 'description'=>'MSMDB 989110210'],
            ['label'=>'Mashhad C', 'colorCode'=>'DB0000', 'param'=>['msc_id'=>'29'], 'description'=>'MSMDC 989110220'],
            ['label'=>'Mashhad D', 'colorCode'=>'FF0000', 'param'=>['msc_id'=>'30'], 'description'=>'MSMDD 989110550'],
            ['label'=>'Qom A', 'colorCode'=>'FF8341', 'param'=>['msc_id'=>'32'], 'description'=>'MSQMA 989180960'],
            ['label'=>'Rahahan A', 'colorCode'=>'8000FF', 'param'=>['msc_id'=>'34'], 'description'=>'MSRHA 989180270'],
            ['label'=>'ShahrKord A', 'colorCode'=>'003783', 'param'=>['msc_id'=>'39'], 'description'=>'MSSKA 989180360'],
            ['label'=>'Shiraz B', 'colorCode'=>'005DB1', 'param'=>['msc_id'=>'41'], 'description'=>'MSSZB 989180770'],
            ['label'=>'Shiraz C', 'colorCode'=>'417BD3', 'param'=>['msc_id'=>'42'], 'description'=>'MSSZC 989180780'],
            ['label'=>'Shiraz D', 'colorCode'=>'6899F5', 'param'=>['msc_id'=>'43'], 'description'=>'MSSZD 989110740'],
            ['label'=>'Yasoj A', 'colorCode'=>'6414FF', 'param'=>['msc_id'=>'49'], 'description'=>'MSYJA 989110790'],
            ['label'=>'Yazd A', 'colorCode'=>'8737FF', 'param'=>['msc_id'=>'50'], 'description'=>'MSYZA 989180350'],
            ['label'=>'Yazd B', 'colorCode'=>'B15DFF', 'param'=>['msc_id'=>'51'], 'description'=>'MSYZB 989180700'],
            ['label'=>'Zahedan B', 'colorCode'=>'DA81FF', 'param'=>['msc_id'=>'52'], 'description'=>'MSZHB 989110540'],
            ['label'=>'TEsfehan A', 'colorCode'=>'004D41', 'param'=>['msc_id'=>'60'], 'description'=>'TSEFA 989110330003'],
            ['label'=>'TShiraz A', 'colorCode'=>'097062', 'param'=>['msc_id'=>'61'], 'description'=>'TSSZA 989110750005'],
            ['label'=>'TMashhad A', 'colorCode'=>'3D9485', 'param'=>['msc_id'=>'62'], 'description'=>'TSMDA 989110510011'],
            ['label'=>'TMashhad B', 'colorCode'=>'64BAAA', 'param'=>['msc_id'=>'63'], 'description'=>'TSMDB 989110510013'],
        ];
    }
}










































