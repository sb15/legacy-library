<?php

namespace Hive\Bee;

class Storage
{
    private $data = array();

    public function save($item)
    {
        if (!empty($item)) {
            $this->data[] = $item;
        }
    }

    public function getData()
    {
        return $this->data;
    }

}