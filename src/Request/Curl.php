<?php

namespace Request;


class Curl
{

    public static function request($method, $url, $params = array())
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //curl_setopt($ch, CURLOPT_REFERER, '');

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } else {
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
        }

        $response = curl_exec($ch);

        $error =  null;
        if (curl_errno($ch)) {
            $error = curl_error($ch);
        } elseif (empty($response)) {
            $error = 'empty_response';
        }

        curl_close($ch);

        if ($error) {
            throw new \Exception($error);
        }

        return $response;
    }

}