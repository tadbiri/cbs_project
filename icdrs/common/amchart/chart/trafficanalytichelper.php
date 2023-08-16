<?php

class TrafficAnalyticHelper{

    /**
    * A method to calculate average.
    * 
    * @param array $list
    * 
    * @return float 2 point average. 
    */
    public static function getAverageOfList($list){
        $listCount = count($list);
        // Get average.
        $average = (float) array_sum($list)/$listCount;
        return round($average, 2, PHP_ROUND_HALF_UP);
    }

    /**
     * Get list and variance object with delete coefficient
     * and then clean deviated numbers based params.
     * 
     * @param array $list.
     * @param object $varianceObject.
     * @param float 2 point $deleteCoefficient an scale to remove.
     * 
     * @return array cleaned list.
     */
    public static function clearListByStandardDeviation($list, $standardDeviation, $average, $deleteCoefficient){
        $_result = [];
        // Sieve list to remove deviated numbers.
        foreach($list as $e){
            $_differenceFromAverage = $e-$average;
            // To convert negative numbers to positive.
            $_differenceFromAverage = ($_differenceFromAverage<0)? $_differenceFromAverage*-1:$_differenceFromAverage;

            // Check for find deviated number.
            $_coefficientStandardDeviation = $standardDeviation*$deleteCoefficient;
            $_coefficientStandardDeviation = round($_coefficientStandardDeviation, 2, PHP_ROUND_HALF_UP);
            if($_differenceFromAverage <= $_coefficientStandardDeviation){
                // It's not deviated.
                $_result[] = $e;
            }
        }
        return $_result;
    }

    /**
     * Get clean average and current point then 
     * Calculate increase/decrease rent for current point.
     * 
     * @param int $currentPoint 
     * @param float $cleanAverage
     * 
     * @return float 2 point increase/decrease rent that can be between -100.00 to infinity.
     */
    public static function getPercentCurrentPoint($currentPoint, $cleanAverage){
        $percent = $currentPoint*100/$cleanAverage;
        $percent = $percent-100;
        return round($percent, 2, PHP_ROUND_HALF_UP);
    }
}