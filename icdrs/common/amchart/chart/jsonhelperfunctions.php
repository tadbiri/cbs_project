<?php
class HelperFunctions{
    public static function ISO8601ToDatetime($datetime){
        $dt = new DateTime($datetime, new DateTimeZone('UTC'));
        // change the timezone of the object without changing its time
        $dt->setTimezone(new DateTimeZone('Asia/Tehran'));
        // format the datetime
        return $dt->format('Y-m-d H:i:s');
    }
    public static function ignoreSecondInTimestamp($timestamp){
        return strtotime(date('Y-m-d H:i:00', $timestamp));
    }

    /**
     * Remove items in an array that not match with regex.
     * 
     * @param array $list
     * @param string $regex eg: '.php' remove all items that not have '.php' sub string.
     * 
     * @return array 
     */
    public static function arrayFilterByRegex($list, $regex){
        $list = array_filter($list, function($file_name) use ($regex){
            if(!preg_match("/$regex$/", $file_name)){
                return false;
            }
            return true;
        });

        // To re-indexing.
        $list = array_values($list);

        return $list;
    }
}