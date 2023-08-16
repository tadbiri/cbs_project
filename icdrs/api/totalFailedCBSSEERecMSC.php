<?php

// Load Error Chart lib for this chart.
require_once dirname(__DIR__, 1)."/common/libs/chart.php";

class totalFailedCBSSEERecMSC extends Chart{
    public function __construct(){
        // Initial chartName
        $this->chartName = strtolower(__CLASS__);

        $this->chartTitle = 'CBS MSC Failed';

        $this->chartLengendHeightPerPixel = 55;

        $this->chartBoxHeightPerPixel = 300;

        $this->graphType = GraphType::Continued;

        $this->query = "SELECT to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') AS \"dateTime\", SUM(e_count) AS \"errorCount\"
                         FROM cbs_see_rec_msc_log
                         WHERE termination_reason_id = '1'  
                         AND  msc_id = ':msc_id:'
                         AND cdr_date_time BETWEEN ':startTime:' AND ':endTime:'
                         GROUP BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI')
                         ORDER BY to_char(cdr_date_time, 'YYYY-MM-DD HH24:MI') ASC";
        
        global $ChartAverageTypeConfig;
        $this->averageList = $ChartAverageTypeConfig[__CLASS__];

        $this->bufferMinuteCountToFetch = 24*60;

        $this->periodForFirstBuildCacheBasedMinute = 2*24*60;

        $this->refreshLastPointBasedMinute = 100;

        $this->waitForFetchAgainBasedSecond = 1;

        $this->chartMinuteShift = 3;

        $this->entities = [
            ['label'=>'Ahvaz A', 'colorCode'=>'9B0085', 'param'=>['msc_id'=>'1'], 'description'=>'MSAHA 989110600'],
            ['label'=>'Ahvaz B', 'colorCode'=>'C039A7', 'param'=>['msc_id'=>'2'], 'description'=>'MSAHB 989110300'],
            ['label'=>'Ahvaz C', 'colorCode'=>'E75FCB', 'param'=>['msc_id'=>'3'], 'description'=>'MSAHC 989110700'],
            ['label'=>'Arak B', 'colorCode'=>'0079EF', 'param'=>['msc_id'=>'4'], 'description'=>'MSAKB 989110830'],
            ['label'=>'Ardabil A', 'colorCode'=>'870800', 'param'=>['msc_id'=>'5'], 'description'=>'MSARA 989180210'],
            ['label'=>'Babol A', 'colorCode'=>'26AE00', 'param'=>['msc_id'=>'8'], 'description'=>'MSBBA 989110450'],
            ['label'=>'Beheshti B', 'colorCode'=>'071D90', 'param'=>['msc_id'=>'9'], 'description'=>'MSBEB 989110290'],
            ['label'=>'Golestan A', 'colorCode'=>'008C00', 'param'=>['msc_id'=>'17'], 'description'=>'MSGOA 989110160'],
            ['label'=>'Hamedan A', 'colorCode'=>'2F3300', 'param'=>['msc_id'=>'18'], 'description'=>'MSHNA 989180330'],
            ['label'=>'Hamedan B', 'colorCode'=>'566000', 'param'=>['msc_id'=>'19'], 'description'=>'MSHNB 989180340'],
            ['label'=>'ILAM A', 'colorCode'=>'AE6DAB', 'param'=>['msc_id'=>'20'], 'description'=>'MSILA 989180410'],
            ['label'=>'Khoramabad A', 'colorCode'=>'8A8F00', 'param'=>['msc_id'=>'21'], 'description'=>'MSKHA 989110760'],
            ['label'=>'Kermanshah A', 'colorCode'=>'C1C23C', 'param'=>['msc_id'=>'24'], 'description'=>'MSKSA 989180720'],
            ['label'=>'Kazemian B', 'colorCode'=>'0050BE', 'param'=>['msc_id'=>'25'], 'description'=>'MSKZB 989110280'],
            ['label'=>'Lahijan A', 'colorCode'=>'BEABB8', 'param'=>['msc_id'=>'26'], 'description'=>'MSLJA 989110140'],
            ['label'=>'Noshahr A', 'colorCode'=>'006B00', 'param'=>['msc_id'=>'31'], 'description'=>'MSNOA 989110170'],
            ['label'=>'Qazvin A', 'colorCode'=>'003298', 'param'=>['msc_id'=>'33'], 'description'=>'MSQZA 989110260'],
            ['label'=>'Rahahan C', 'colorCode'=>'009ED7', 'param'=>['msc_id'=>'35'], 'description'=>'MSRHC 989110240'],
            ['label'=>'Rasht B', 'colorCode'=>'988693', 'param'=>['msc_id'=>'36'], 'description'=>'MSRSB 989110130'],
            ['label'=>'Semnan B', 'colorCode'=>'0048B4', 'param'=>['msc_id'=>'37'], 'description'=>'MSSEB 989110800'],
            ['label'=>'Sanandaj A', 'colorCode'=>'9CBB17', 'param'=>['msc_id'=>'38'], 'description'=>'MSSJA 989180420'],
            ['label'=>'Sari A', 'colorCode'=>'004B00', 'param'=>['msc_id'=>'40'], 'description'=>'MSSRA 989110100'],
            ['label'=>'Tabriz A', 'colorCode'=>'FB7421', 'param'=>['msc_id'=>'44'], 'description'=>'MSTZA 989110400'],
            ['label'=>'Tabriz B', 'colorCode'=>'D25200', 'param'=>['msc_id'=>'45'], 'description'=>'MSTZB 989180440'],
            ['label'=>'Tabriz C', 'colorCode'=>'A92F00', 'param'=>['msc_id'=>'46'], 'description'=>'MSTZC 989110880'],
            ['label'=>'Orumieh A', 'colorCode'=>'52424D', 'param'=>['msc_id'=>'47'], 'description'=>'MSURA 989180220'],
            ['label'=>'Orumieh B', 'colorCode'=>'74636F', 'param'=>['msc_id'=>'48'], 'description'=>'MSURB 989110460'],
            ['label'=>'Zanjan A', 'colorCode'=>'0060D1', 'param'=>['msc_id'=>'53'], 'description'=>'MSZJA 989110270'],
            ['label'=>'TAhvaz A', 'colorCode'=>'005246', 'param'=>['msc_id'=>'54'], 'description'=>'TSAHA 989110620002'],
            ['label'=>'TBabol A', 'colorCode'=>'00514E', 'param'=>['msc_id'=>'55'], 'description'=>'TSBBA 989110120004'],
            ['label'=>'TBeheshti B', 'colorCode'=>'034F54', 'param'=>['msc_id'=>'56'], 'description'=>'TSBHB 989110120001'],
            ['label'=>'THamedan A', 'colorCode'=>'154D58', 'param'=>['msc_id'=>'57'], 'description'=>'TSHNA 989110850001'],
            ['label'=>'TRahahan B', 'colorCode'=>'234A59', 'param'=>['msc_id'=>'58'], 'description'=>'TSRHB 989110120000'],
            ['label'=>'TTabriz A', 'colorCode'=>'2F4858', 'param'=>['msc_id'=>'59'], 'description'=>'TSTZA 989110420006'],
            ['label'=>'Tohid B', 'colorCode'=>'0078D5', 'param'=>['msc_id'=>'64'], 'description'=>'MSTDB 989110250'],
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
