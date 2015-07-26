<?php

class Grabber_Storage_Dump implements Grabber_Storage_Interface
{
    public function save($placeInfo)
    {
        Zend_Debug::dump($placeInfo);
    }
}