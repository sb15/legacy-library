<?php

namespace Hive\Field;

class LiveinternetRu
{

    private $browser = null;
    private $task = null;

    public function __construct($browser = null, $task = null)
    {
        $this->setBrowser($browser);
        $this->setTask($task);
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setBrowser($browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return \Zend_Browser_Console
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    public function task($name, $options = array())
    {
        if ($name == 'views') {
            $this->viewsTask($options);
        }
    }

    public function viewsTask($options = array())
    {
        $html = $this->getBrowser()->doRequest('http://www.liveinternet.ru/stat/ucanpurchase.ru/index.html');

        return array('frreter' => 'ter');
    }

}
