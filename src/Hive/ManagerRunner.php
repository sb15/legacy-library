<?php

namespace Hive;

class HiveRunner
{
    public function __construct()
    {
        global $argv;
        if (count($argv) > 2) {
            $this->consoleRun();
        } else {
            $this->dbRun();
        }
    }

    public function dbRun()
    {
        $db = \Zend_Db::factory('Pdo_Mysql', Database::getDbParams());

        global $argv;

        $tasks = $db->fetchAll("
            SELECT *
                FROM queue_periodically
                WHERE locked = false AND
                    (TIME_TO_SEC(TIMEDIFF( NOW(), last_update )) > repeat_interval
                    OR
                    TIME_TO_SEC(TIMEDIFF( NOW(), last_update )) IS NULL)");

        if (!$tasks) {
            echo 'no task';
            return;
        }

        $taskIds = array();
        foreach ($tasks as $task) {
            $taskIds[] = $task['id'];
        }

        $sql = "UPDATE queue_periodically SET locked = true WHERE id IN (" . implode($taskIds, ",") . ")";
        $db->query($sql);

        foreach ($tasks as $task) {

            $lockedTime = date("Y-m-d H:i:s");
            $db->update('queue_periodically', array(
                'locked_time' => $lockedTime
            ), "id = " . $task['id']);

            $taskOptionEx = $db->fetchOne($task['task_condition']);

            if (!$taskOptionEx) {
                echo 'no task options ex';
				$db->update('queue_periodically', array('locked' => 0), "id = " . $task['id']);
                continue;
            }

            if (!is_numeric($taskOptionEx)) {
                $task['task_options'] .= $taskOptionEx;
            }

            $argv[1] = $task['task_name'];
            $argv[2] = $task['task_options'];

            $lastError = null;
            try {
                $this->consoleRun($db);
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
            }

            $lastUpdateTime = date("Y-m-d H:i:s");
            $db->update('queue_periodically', array(
                'locked' => 0,
                'last_update' => $lastUpdateTime,
                'execution_time' => strtotime($lastUpdateTime) - strtotime($lockedTime),
                'last_error' => $lastError
            ), "id = " . $task['id']);

        }

    }

    public function consoleRun($db = null)
    {
        new Manager($db);
    }
}

require_once 'CoreAutoloader.php';
\CoreAutoloader::init();

new HiveRunner();