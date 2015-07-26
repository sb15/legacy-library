<?php

class Utils_Date 
{
    public static function getDate($now = null)
    {
        if ((is_null($now))) {
            return date("Y-m-d H:i:s");
        } elseif (is_numeric($now)) {
            return date("Y-m-d H:i:s", $now);
        } elseif (is_string($now)) {
            return date("Y-m-d H:i:s", strtotime($now));
        }
        return null;
    }
}