<?php

class Utils_Form 
{
    public static function getMultipleOptionsFromData($data, $keyField, $valueField)
    {
        $result = array();
        foreach ($data as $k => $v) {
            $result[$v[$keyField]] = $v[$valueField];
        }
        return $result;
    }
}