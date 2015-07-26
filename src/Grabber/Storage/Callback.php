<?php

class Grabber_Storage_Callback implements Grabber_Storage_Interface
{

    protected $_db = null;
    protected $callback = null;

    public function __construct($db, $callback)
    {
        $this->_db = $db;
        $this->callback = $callback;
    }

    public function save($placeInfo)
    {
        $func = $this->callback;
        $func($placeInfo)->__invoke();
    }
}