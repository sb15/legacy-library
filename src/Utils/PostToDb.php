<?php

class Utils_PostToDb
{

    public static function convert($array)
    {
        $filter = new \Zend_Filter_Word_CamelCaseToUnderscore();
        $result = array();
        foreach ($array as $key => $value) {
            $result[strtolower($filter->filter($key))] = $value;
        }
        return $result;
    }

}