<?php

namespace Incubator;

class GeoLocationYandex
{
    public static function getLocation($address)
    {
        $address = urlencode($address);
        $url = "http://api-maps.yandex.ru/1.1.21/xml/Geocoder/Geocoder.xml?key=AFKqbE0BAAAAWyKbfgIAP3WQ752MmJjCSU4whyLS-Ik7-dYAAAAAAAAAAADvD_BB3GK7scwIUq9-CBaOtMjgjQ==&geocode={$address}&spn=0.30899%2C0.064054&results=1";
        $content = file_get_contents($url);

        if (preg_match('#return \[G?R\(\[([\d\.]+,[\d\.]+)\]#uis', $content, $m)) {
            $data = $m[1];
            $data = explode(",", $data);
            $data = array_reverse($data);
            return implode(",", $data);
        }

        return false;
    }
}