<?php

namespace Hive\Field;

class LiTask extends \Hive\Task\AbstractTask
{
    public function login()
    {
        $url = 'http://www.liveinternet.ru/stat/' . $this->getOption('username') . '/index.html';
        $this->getBrowser()->doRequest($url);
        $form = $this->getBrowser()->getForm(null, 'form');

        $form['fields']['url'] = "http://" . $this->getOption('username') . "/";
        $form['fields']['password'] = $this->getOption('password');

        $this->getBrowser()->sentForm($form);
    }

    public function getStartUrl()
    {
        return 'http://www.liveinternet.ru/stat/' . $this->getOption('username') . '/queries.html?date=' . $this->getOption('date') . '&per_page=100';
    }

    public function grabList()
    {
        return array(
            'item' => $this->grabItem()
        );
    }

    public function grabItem()
    {
        $dom = $this->getBrowser()->getDomParserNokogiri();
        $tableRow = $dom->get('table[bgcolor=#e8e8e8] tr');

        $result = array();
        foreach ($tableRow as $row) {

            $words = null;
            $clicks = null;

            if (isset($row['td']['1']['label']['0']['a']['#text'])) {
                $words = $row['td']['1']['label']['0']['a']['#text'];
            } elseif (isset($row['td']['1']['a']['0']['#text'])) {
                $words = $row['td']['1']['a']['0']['#text'];
            }

            if (isset($row['td']['2']['#text'])) {
                $clicks = $row['td']['2']['#text'];
            }

            if ($words && $clicks) {
                $result[] = array(
                    'words' => $words,
                    'clicks' => $clicks
                );
            }
        }

        return $result;
    }

}
