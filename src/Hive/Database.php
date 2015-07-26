<?php

namespace Hive;

class Database
{
    public static function getDbParams()
    {
        if (getenv('APPLICATION_ENV') == 'development') {
            return array(
                'host'     => 'localhost',
                'username' => 'root',
                'password' => 'admin',
                'dbname'   => 'hive'
            );
        } else {
            return array(
                'host'     => 'localhost',
                'username' => 'hive',
                'password' => 'MZ4HPFMxZyrEeNUj',
                'dbname'   => 'hive'
            );
        }
    }
}