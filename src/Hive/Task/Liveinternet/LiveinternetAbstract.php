<?php

namespace Hive\Task\Liveinternet;

abstract class LiveinternetAbstract extends \Hive\Task\AbstractTask
{
    public function login()
    {
        $url = 'http://www.liveinternet.ru/stat/' . urlencode($this->getOption('username')) . '/index.html';
        $this->getBrowser()->doRequest($url);
        $form = $this->getBrowser()->getForm(null, 'form');

        $form['fields']['url'] = "http://" . $this->getOption('username') . "/";
        $form['fields']['password'] = $this->getOption('password');

        $this->getBrowser()->sentForm($form);
    }

    public function grabList()
    {
        return array(
            'item' => $this->grabItem()
        );
    }

}
