<?php

class Grabber_Storage_Db implements Grabber_Storage_Interface
{

    protected $_db = null;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function save($placeInfo)
    {
        try {
            $this->_db->insert('place_sandbox', array(
                    'content' => serialize($placeInfo),
                    'create_time' => Utils_Date::getDate()
            ));
        } catch (Exception $e) {}
    }
}