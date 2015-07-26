<?php

namespace Oauth2;

class Consumer
{
    /**
     * @var array
     */
    protected $_config = null;

    public function __construct($options = null)
    {
        $this->_config = $options;
    }

    public function redirect(array $customServiceParameters = null)
    {
        $common = array();
        $params = array_merge($common, $customServiceParameters);

        $url = $this->_config['userAuthorizationUrl'] . '?';
        $url .= http_build_query($params, null, '&');

        header('Location: ' . $url);
        exit(1);
    }

    public function request($method, $url, $params = array())
    {

        return \Request\Curl::request($method, $url, $params);

        /*$context = null;
        $data = http_build_query($params);

        if ($method == 'POST') {
            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $data
                )
            );
            $context  = stream_context_create($opts);
        } else {
            $url .= '?' . $data;
        }

        return file_get_contents($url, false, $context);*/
    }

    public function getAccessToken(array $customServiceParameters = null)
    {
        $response = $this->request('POST', $this->_config['accessTokenUrl'], $customServiceParameters);
        return (array) json_decode($response);
    }

    public function getIdentity(array $customServiceParameters = null, $method = 'GET')
    {
        $response = $this->request($method, $this->_config['requestDataUrl'], $customServiceParameters);
        return (array) json_decode($response);
    }

}
