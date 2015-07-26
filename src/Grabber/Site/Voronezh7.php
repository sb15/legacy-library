<?php

require_once 'Grabber/Abstract.php';
require_once 'Grabber/PlaceInfo.php';

class Grabber_Site_Voronezh7 extends Grabber_Abstract
{

    public function grabItem($url, $content)
    {
        $saw = $this->getNokogiri($content);
        $vcard = $saw->get('table tr td[width=800px]');

        $placeInfo = new Grabber_PlaceInfo();
        $placeInfo->setSource($url);

        $temp = $this->getElementValue($vcard->get('h1'));
        if (strpos($temp, ",") !== false) {
            $temp = explode(",", $temp);
            $placeInfo->setName(array_shift($temp));
            $temp = explode("-", $temp[0]);
            $placeInfo->setType(array_shift($temp));
        } elseif (strpos($temp, "-") !== false) {
            $temp = explode("-", $temp);
            $placeInfo->setName(array_shift($temp));
            // type
            if (preg_match("#Подкатегории: ?(.*?)</p>#uis", $content, $m)) {
                $subcats = $m[1];
                if (preg_match("#<a[^>]*>.*?</a>#uis", $subcats)) {
                    $placeInfo->setType($m[1]);
                }
            }
        }

        $logo = $vcard->get('img[class=border1]');
        $logo = $logo->toArray();
        if (isset($logo[0]['src'])) {
            $placeInfo->setLogoUrl($logo[0]['src']);
        }

        $city = '';
        if (preg_match("#Город: ?<a[^>]*>(.*?)</a>#uis", $content, $m)) {
            $city = $m[1];
        }
        if (preg_match("#Адрес: ?(.*?)\n#uis", $content, $m)) {
            $placeInfo->setAddress($city. ','. $m[1]);
        }
        if (preg_match("#Телефон: ?(.*?)\n#uis", $content, $m)) {
            $placeInfo->setPhones($m[1]);
        }
        if (preg_match("#E-mail: ?(.*?)\n#uis", $content, $m)) {
            $placeInfo->setEmails($m[1]);
        }
        if (preg_match("#Сайт: ?<a[^>]*>(.*?)</a>#uis", $content, $m)) {
            $placeInfo->setSite($m[1]);
        }


        if (preg_match("#YMaps\.GeoPoint\(([^\)]*)\)#uis", $content, $m)) {
            //$placeInfo->setLocation(implode(",",array_reverse(explode(",", $m[1]))));
        }

        return $placeInfo;
    }

    public function grabList($content)
    {
        $saw = $this->getNokogiri($content);
        $blocks = $saw->get("table.price tr td a")->toArray();
        $result = array();
        foreach ($blocks as $v) {
            if (!is_numeric($v['#text'])) {
                $result[] = $v['href'];
            }
        }
        $result = array_unique($result);
        return $result;
    }

}