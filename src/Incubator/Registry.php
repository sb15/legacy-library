<?php

namespace Incubator;

class Registry
{
    private static $vals = array();

    public function get($key)
    {
        return self::$vals[$key];
    }

    public function set($key, $value)
    {
        self::$vals[$key] = $value;
    }

}