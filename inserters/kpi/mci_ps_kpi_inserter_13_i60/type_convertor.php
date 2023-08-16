<?php
class TypeConvertorHelper
{
    /**
     * array to object
     * @param array $array
     * @return object 
     */
    public static function arrayToObject($array)
    {
        $obj = new stdClass;
        foreach ($array as $k => $v) {
            if (strlen($k)) {
                if (is_array($v)) {
                    $obj->{$k} = self::arrayToObject($v); //RECURSION
                } else {
                    $obj->{$k} = $v;
                }
            }
        }
        return $obj;
    }

    /**
     * get index of key/vay from a list.
     * [['t'=>'1', 'g'=>'3'], ['t'=>'6', 'g'=>'f']] ['t','6'] => 1
     * @param array $arrayList list
     * @param array $keyValue ['key','value']
     * @return int index of match, not match -1
     */
    public static function getIndexOfKeyValueInArray($arrayList, $keyValue)
    {
        $_count = 0;
        foreach ($arrayList as $_object) {
            if (isset($_object[$keyValue[0]])) {
                if ($_object[$keyValue[0]] == $keyValue[1]) {
                    return $_count;
                }
            }
            $_count++;
        }
        return -1;
    }

    /**
     * get index of value from a list.
     * [6,7,83] 7 => 1
     * @param array $array list,
     * @param int $item item
     * @return int not found is -1.
     */
    public static function getIndexOfValueInArray($array, $item)
    {
        $index = array_search($item, $array);
        if (is_numeric($index)) {
            return $index;
        } else {
            return -1;
        }
    }

    /**
     * simple intersect.
     * [1,2,3,5] [1,5,6] => [1,5]
     * @param array $a list1
     * @param array $b list2
     * @return array result
     */
    public static function intersect($a, $b)
    {
        $_intersect = array_intersect($a, $b);
        if (count($_intersect) == 0) {
            return null;
        }
        $_result = array();
        foreach ($_intersect as $key => $value) {
            array_push($_result, $value);
        }
        return $_result;
    }

    /**
     * convert a list to CSV
     * [1,2,3] => "1,2,3"
     * @param array $list list
     * @return string csv format.
     */
    public static function arrayToCSV($list, $enableQuotation = false)
    {
        $_string = "";
        foreach ($list as $item) {
            if ($enableQuotation && !is_numeric($item)) {
                $_string .= "'" . $item . "'" . ",";
            } else {
                $_string .= $item . ",";
            }
        }
        $_string = rtrim($_string, ",");
        return $_string;
    }

    /**
     * Delete a value in a simple array.
     * @param array $list list
     * @param string $delItem value for delete.
     * @return bool for success find and remove be true.
     */
    public static function arrayDeleteValue(&$list, $delItem)
    {
        $_index = array_search($delItem, $list);
        if (is_numeric($_index)) {
            unset($list[array_search($delItem, $list)]);
            // Rebuild array.
            $_temp = [];
            foreach ($list as $e) {
                array_push($_temp, $e);
            }
            $list = $_temp;
            return true;
        }
        return false;
    }

    /**
     * romve an array in a simple array,
     * example [[1,2,3],[4,5,7]] => delete 1 => [[1,2,3]]
     * @param array $array array
     * @param int $index index for remove
     */
    public static function deleteArrayInsideArrayByIndex(&$array, $index)
    {
        // Remove insideArray.
        unset($array[$index]);
        // Generate list again.
        $_temp = [];
        foreach ($array as $e) {
            array_push($_temp, $e);
        }
        $array = $_temp;
    }

    /**
     * get a simple array pointer and fix array index in,
     * @param array $array array.
     */
    public static function repairArrayIndex(&$array)
    {
        $_temp = [];
        foreach ($array as $e) {
            array_push($_temp, $e);
        }
        $array = $_temp;
    }

    /**
     * get an array from arrayList by key.
     * [['t'=>'1', 'g'=>'3'], ['t'=>'6', 'g'=>'f']] t => [1,6] 
     * errors in not found key just in one instance.
     * @param array $array list
     * @param string $key key
     * @return mixed keyArray in error return -1 in success array. 
     */
    public static function getKeyListFromArrayList($array, $key)
    {
        // detect error.
        foreach ($array as $e) {
            if (!isset($e[$key])) {
                return -1;
            }
        }
        $_result = [];
        foreach ($array as $e) {
            array_push($_result, $e[$key]);
        }
        return $_result;
    }

    /**
     * get a string GET param and convert to array.
     * id_product=7191&id_product_attribute=30993 => [id_product=>'7191', id_product_attribute=>30993]
     * @param string $params param
     * @return array 
     */
    public static function convertGetParamToArray($params)
    {
        $_result = [];
        foreach (explode('&', $params) as $param) {
            $_paramObject  = explode('=', $param);
            $_result[$_paramObject[0]] = $_paramObject[1];
        }
        return $_result;
    }

    /**
     * get seconds and convert it to time.
     * @param int $seconds time per second eg: 125
     * @return string '00:02:05'
     */
    public static function convertSecondsToTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);

        return sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    }

    /**
     * check that a small simple array exist in a bigger array.
     * @param array $child small simple array. eg: [1,2,3]
     * @param array $main bigger simple array. eq: [45,24,1,45,123,2,1,2]
     */
    public static function isExistSimpleArrayInSimpleArray($child, $main)
    {
        foreach ($child as $c) {
            $i = self::getIndexOfValueInArray($main, $c);
            if ($i < 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * check that a entity of small simple array exist in a bigger array.
     * @param array $child small simple array. eg: [1,2,3]
     * @param array $main bigger simple array. eq: [45,24,1]
     * @return bool true it this case
     */
    public static function isExistAnEntityFromSimpleArrayInSimpleArray($child, $main)
    {
        foreach ($child as $c) {
            $i = self::getIndexOfValueInArray($main, $c);
            if ($i >= 0) {
                return true;
            }
        }
        return false;
    }

    public static function isExactHalf($_num)
    {
        $_mathInt = $_num - 0.5;
        if (((int)$_mathInt - $_mathInt) == 0) {
            return true;
        }
        return false;
    }

    public static function arrayToCoordinates($list)
    {
        $listUnique = array_unique($list);

        $kl = [];
        $vl = [];
        foreach ($listUnique as $k => $v) {
            $kl[] = $k;
            $vl[] = $v;
        }
        
        $res = [];
        for ($i = 0; $i < count($kl); $i++) {
            if ($i < count($kl) - 1) {
                $res[] = [$vl[$i], $kl[$i], $kl[$i + 1] - 1];
            } else {
                $res[] = [$vl[$i], $kl[$i], count($list) - 1];
            }
        }

        return $res;
    }

    public static function getByIndexMuliple($list, $key, $val){
        $result = [];
        for($i=0; $i < count($list); $i++){
            if($list[$i][$key] == $val){
                $result[] = $list[$i];
            }
        }
        return $result;
    }
}