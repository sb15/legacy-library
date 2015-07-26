<?php

class Utils_Host {

    public static function formatHost($url)
    {
        $url = trim($url);

        if (!preg_match("#^http#is", $url)) {
            $url = "http://" . $url;
        }

        $parts = parse_url($url);
        if (count($parts)) {
            return "{$parts['scheme']}://{$parts['host']}";
        }
        return false;
    }

}