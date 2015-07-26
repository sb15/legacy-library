<?php

namespace Hive;

class Hive
{

    private $manager = null;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \Hive\Manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    public function run($taskOptions)
    {
        $browser  = new \Zend_Browser_Console();

        $bee = new \Hive\Bee();

        $taskName = '\\Hive\\Task\\' . $taskOptions['task_name'];

        $task = new $taskName();
        $task->setOptions($taskOptions);
        $task->setBee($bee);

        $bee->setHive($this);
        $bee->setBrowser($browser);
        $bee->setTask($task);
        $bee->fly();
    }

    public function returnData($data, \Hive\Task\AbstractTask $task)
    {
        // task struct

        foreach ($data as $page) {
            foreach ($page as $line) {
                if ($task->getStrategy() == 'insert') {
                    $this->getManager()->getDb()->insert(
                        $task->getDestination(),
                        $task->getDestinationOptions($line)
                    );
                } else {
                    $this->getManager()->getDb()->update(
                        $task->getDestination(),
                        $task->getDestinationOptions($line),
                        $task->getDestinationUpdate()
                    );
                }
            }
        }
        //file_put_contents("d:\\fsdfsdf345", print_r($data, true));
    }

}


