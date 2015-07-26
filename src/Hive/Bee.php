<?php

namespace Hive;

class Bee
{

    private $hive = null;
    private $field = null;
    private $browser = null;
    private $task = null;

    private $storage = null;

    public function __construct($hive = null, $field = null, $browser = null, $task = null)
    {
        $this->setHive($hive);
        $this->setField($field);
        $this->setBrowser($browser);
        $this->setTask($task);
        $this->setStorage(new \Hive\Bee\Storage());
    }

    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return \Hive\Bee\Storage
     */
    public function getStorage()
    {
        return $this->storage;
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    /**
     * @return \Hive\Field\LiTask
     */
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

    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return \Hive\Field\LiveinternetRu
     */
    public function getField()
    {
        return $this->field;
    }

    public function setHive($hive)
    {
        $this->hive = $hive;
    }

    /**
     * @return \Hive\Hive
     */
    public function getHive()
    {
        return $this->hive;
    }


    public function isAbsoluteUrl($url)
    {
        // exception @todo
        if (preg_match("#^https?:#is", $url)) {
            return true;
        } else {
            return false;
        }
    }

    public function startGrabFromUrl($url, \Hive\Task\AbstractTask $task)
    {
        $nextPageUrl = $url;
        $page = 1;

        $relativeUrl = dirname($url);

        do {
            $content = $this->getBrowser()->doRequest($nextPageUrl);

            $currentUrl = $nextPageUrl;
            $nextPageUrl = $task->getNextPage($content, $page);

            if ($nextPageUrl && !$this->isAbsoluteUrl($nextPageUrl)) {
                $nextPageUrl = $relativeUrl . '/' . $nextPageUrl;
            }

            $page++;

            $listUrl = $task->grabList($content, $currentUrl);

            if (array_key_exists('urls', $listUrl)) {

                foreach ($listUrl['urls'] as $url) {
                    $this->getBrowser->doRequest($url);
                    $item = $task->grabItem();
                    $this->getStorage()->save($item);
                }

            } elseif (array_key_exists('item', $listUrl)) {

                $this->getStorage()->save($listUrl['item']);

            }

        } while ($nextPageUrl !== false);
    }

    public function fly()
    {
        $task = $this->getTask();
        $task->login();

        $startUrl = $task->getStartUrl();
        $this->startGrabFromUrl($startUrl, $task);

        $this->getHive()->returnData($this->getStorage()->getData(), $this->getTask());
    }
    
}
