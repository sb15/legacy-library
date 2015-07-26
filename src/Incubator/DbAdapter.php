<?php

namespace Incubator;

use Zend\Db\Adapter\Adapter;

class DbAdapter extends \Zend\Db\Adapter\Adapter
{
    public function fetchOne()
    {
        echo 1;die;
    }
}
