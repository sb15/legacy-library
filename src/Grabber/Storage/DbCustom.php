<?php

class Grabber_Storage_DbCustom implements Grabber_Storage_Interface
{

    protected $_db = null;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function save($placeInfo)
    {
        foreach ($placeInfo as $row) {
            echo "0,";
            foreach ($row as $col) {
                echo "\"{$col}\",";
            }
            echo "0<br />";
        }
    }
}