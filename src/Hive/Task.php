<?php

namespace Hive;

class Task
{

    private $options = array();

    public function __construct($options)
    {
        $this->setOptions($options);
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }


}
