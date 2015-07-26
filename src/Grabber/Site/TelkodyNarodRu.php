<?php

require_once 'Grabber/Abstract.php';
require_once 'Grabber/PlaceInfo.php';

class Grabber_Site_TelkodyNarodRu extends Grabber_Abstract
{

    public function grabItem($url, $content)
    {
        $saw = $this->getNokogiri($content);
        $table = $saw->get('table[width=95%] tr')->toArray();

        $result = array();
        for ($i = 1; $i < count($table); $i++) {
            $td = $table[$i]['td'];
            $codes = explode(",", $td[2]['#text']);

            foreach ($codes as $code) {
                $result[] = array(
                    $td[0]['#text'],
                    $code
                );
            }

        }
        return $result;
    }

    public function grabList($content, $url = null)
    {
        return array($url);
    }

}