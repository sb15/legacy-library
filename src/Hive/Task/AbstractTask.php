<?php

namespace Hive\Task;

abstract class AbstractTask
{

    private $bee = null;
    private $options = array();

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getOption($name)
    {
        return $this->options[$name];
    }

    public function setBee($bee)
    {
        $this->bee = $bee;
    }

    /**
     * @return \Hive\Bee
     */
    public function getBee()
    {
        return $this->bee;
    }

    /**
     * @return \Zend_Browser_Console
     */
    public function getBrowser()
    {
        return $this->getBee()->getBrowser();
    }

    public function getDestination()
    {
        return $this->getOption('destination');
    }

    public function getStrategy()
    {
        return 'insert';
    }

    abstract public function login();
    abstract public function getStartUrl();
    abstract public function grabList();
    abstract public function grabItem();
    abstract public function getDestinationOptions($data = array());

    public function getNextPage($content, $currentPage = 1)
    {
        $nextPage = $currentPage + 1; // strategy add

        $saw = $this->getBrowser()->getDomParserNokogiri($content);
        $links = $saw->get('a')->toArray();
        foreach ($links as $link) {
            if (isset($link['#text']) && $link['#text'] == $nextPage) {
                return $link['href'];
                //} elseif (preg_match("#" . $nextPage . "\.html#is", $link['href'])) {
            } elseif (preg_match("#=" . $nextPage . "\.html#is", $link['href'])) {
                return $link['href'];
            }
        }
        return false;
    }

}
