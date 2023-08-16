<?php
/**
 * Define of averageType for charts.
 * 
 * `name` string -> name of average type.
 * [a-z]+ pattern.
 * 
 * 
 * `label` string -> name of average type,
 *  No any rules to naming, it's used in layout show.
 * 
 * `PeriodBasedMinuteToShow` int -> period time to show charts.
 * 
 * `timerLayountIntervalPerSecond` int -> config timer interval to update charts.
 * 
 */
class ChartAverageType{

    const OneHour = [
        'name'=>'onehour',
        'label'=> '1 Hour',
        'PeriodBasedMinuteToShow'=>60,
        'timerLayountIntervalPerSecond'=>60,
    ];

    const TwoHour = [
        'name'=>'twohour',
        'label'=> '2 Hour',
        'PeriodBasedMinuteToShow'=>120,
        'timerLayountIntervalPerSecond'=>60,
    ];

    const ThreeHour = [
        'name'=>'threehour',
        'label'=> '3 Hour',
        'PeriodBasedMinuteToShow'=>180,
        'timerLayountIntervalPerSecond'=>60,
    ];

    const OneDay = [
        'name'=>'oneday',
        'label'=> '1 Day',
        'PeriodBasedMinuteToShow'=>60*25,
        'timerLayountIntervalPerSecond'=>120,
    ];
    
    const ThreeDays = [
        'name'=>'treedays',
        'label'=> '3 Days',
        'PeriodBasedMinuteToShow'=>60*24*3,
        'timerLayountIntervalPerSecond'=>0,
    ];

    const OneWeek = [
        'name'=>'oneweek',
        'label'=> '1 Week',
        'PeriodBasedMinuteToShow'=>7*24*60,
        'timerLayountIntervalPerSecond'=>0,
    ];

    const TenDays = [
        'name'=>'tendays',
        'label'=> '10 Days',
        'PeriodBasedMinuteToShow'=>10*24*60,
        'timerLayountIntervalPerSecond'=>0,
    ];
    const OneMonth = [
        'name'=>'onemonth',
        'label'=> '1 Month',
        'PeriodBasedMinuteToShow'=>30*24*60,
        'timerLayountIntervalPerSecond'=>0,
    ];

    static function getConstants() {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    static function getAll(){
        return self::getConstants();
    }

    static function getByName($typeName){
        $list = self::getConstants();
        foreach($list as $e){
            if(strtolower($e['name']) == $typeName){
                return $e;
            }
        }
        return null;
    }
}
