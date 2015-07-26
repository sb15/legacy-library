<?php

namespace Hive;

class Manager
{
    private $db = null;

    /**
     * @return \Zend_Db_Adapter
     */
    public function getDb()
    {
        return $this->db;
    }

    public function __construct($db = null)
    {

        if (!$db) {
            $this->db = \Zend_Db::factory('Pdo_Mysql', Database::getDbParams());
        } else {
            $this->db = $db;
        }

        global $argv;

        $options = array();
        $options['task_name'] = $argv[1];

        $taskOptions = $argv[2];

        $temp = explode(";", $taskOptions);

        foreach ($temp as $k => $v) {
            $newPair = explode("=", $v);
            $options[$newPair[0]] = $newPair[1];
        }


        $hive = new Hive($this);
        $hive->run($options);
    }

}
