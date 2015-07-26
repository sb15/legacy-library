<?php

require_once 'Grabber/Abstract.php';


class Grabber_Site_GoBars extends Grabber_Abstract
{

    public $tmpPath = 'C:\\WebServers\\home\\restplace.dev\\rawData\\';

    public function grabItem($url, $content)
    {
        $saw = $this->getNokogiri($content);

        $vcard = $saw->get('div.vcard');

     //   Zend_Debug::dump($vcard->toArray());

        $result = array();
        $result['name'] = $this->getElementValue($vcard->get('span.org')->toArray());
        $result['type'] = $this->getElementValue($vcard->get('span.category')->toArray());
        $result['address'] = $this->getElementValue($vcard->get('span.locality')->toArray()) . "," . $this->getElementValue($vcard->get('span.street-address')->toArray());

        $result['metro'] = $this->getElementValue($vcard->get('a.metro')->toArray());

        $result['location'] = $this->getElementValue($vcard->get('span.latitude span.value-title')->toArray(), 'title') . ',' .
                              $this->getElementValue($vcard->get('span.longitude span.value-title')->toArray(), 'title');

        $result['phones'] = $this->getElementValue($vcard->get('div.tel abbr.value')->toArray());

        $result['site'] = $this->getElementValue($vcard->get('span.url a')->toArray(), 'href');

        $result['worktime'] = $this->getElementValue($vcard->get('span.workhours')->toArray());

        $result['source'] = $url;

        $result['additional'] = '';
        foreach ($vcard->get('p') as $p) {
            if (array_key_exists('0', $p['strong']) && $p['strong'][0]['#text'] == 'Особенности: ') {
                $result['additional'] = $p['#text'][0];
                break;
            }
        }
        //Zend_Debug::dump($result);die;

        //$result['additional'] = $this->getElementValue($vcard->get('span.workhours')->toArray());

        file_put_contents($this->tmpPath . 'gobars_' . sha1($url), serialize($result));
        usleep(500000);
    }

    public function grabList($content)
    {
        $saw = $this->getNokogiri($content);
        $blocks = $saw->get("div.kitchen_block h2 a")->toArray();
        $result = array();
        foreach ($blocks as $v) {
            $result[] = $v['href'];
        }
        return $result;
    }

    public function grabData($url)
    {
        $pageNum = 2;

        $nextUrl = $url;

        do {

            $content = $this->getContent($nextUrl, 'windows-1251');
            $saw = $this->getNokogiri($content);

            $nextUrl = $saw->get('a.next')->toArray();
            if (array_key_exists('0', $nextUrl)) {
                $nextUrl = $nextUrl[0]['href'];
                $nextUrl = preg_replace("#page_\d+\.#uis", "page_{$pageNum}.", $nextUrl);

                if (strpos($content, "page_{$pageNum}.html") === false) {
                    $nextUrl = null;
                }

            } else {
                $nextUrl = null;
            }

            $pageNum++;

            $detailInfoUrl = $this->grabList($content);
            foreach ($detailInfoUrl as $detailUrl) {

                echo "Item: {$detailUrl}\n";

                $content = $this->getContent($detailUrl, 'windows-1251');
                $this->grabItem($detailUrl, $content);

            }

            echo "Dir: {$nextUrl}\n";
            usleep(500000);

        } while ($nextUrl);

    }

}