<?php

namespace Hive\Task\Liveinternet;

class Words extends \Hive\Task\Liveinternet\LiveinternetAbstract
{
    public function getStartUrl()
    {
        return 'http://www.liveinternet.ru/stat/' . urlencode($this->getOption('username')) . '/queries.html?date=' . $this->getOption('date') . '&per_page=100';
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

    public function getNextPage($content, $currentPage = 1)
    {
        $nextPage = $currentPage + 1;
        $saw = $this->getBrowser()->getDomParserNokogiri($content);
        $links = $saw->get('a')->toArray();
        foreach ($links as $link) {
            if (isset($link['#text']) && is_numeric($link['#text']) && $link['#text'] == $nextPage) {
                return $link['href'];
            } elseif (preg_match("#=" . $nextPage . "\.html#is", $link['href'])) {
                return $link['href'];
            }
        }
        return false;
    }

    public function getDestinationOptions($data = array())
    {
        return array(
            'project_id' => $this->getOption('project_id'),
            'words' => $data['words'],
            'create_date' => $this->getOption('date'),
            'clicks' => $data['clicks']
        );
    }
    
}
