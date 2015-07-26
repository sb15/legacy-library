<?php

namespace Oauth2\Adapter;

class Yandex implements \Oauth2\Adapter\CommonInterface
{
    protected $_consumerName = 'yandex';

    protected $_config = array(
        'consumerId'            => '',
        'consumerSecret'        => '',

        'userAuthorizationUrl'  => 'https://oauth.yandex.ru/authorize',
        'accessTokenUrl'        => 'https://oauth.yandex.ru/token',
        'requestDataUrl'        => 'https://login.yandex.ru/info',
        'responseType'          => 'code',
    );

    public function __construct($options)
    {
        $this->_config = array_merge($this->_config, $options);
    }

    public function authenticate()
    {

        $consumer = new \Oauth2\Consumer($this->_config);

        $config = $this->_config;

        $authorizationUrl   = $config['userAuthorizationUrl'];
        $accessTokenUrl     = $config['accessTokenUrl'];
        $clientId           = $config['consumerId'];
        $clientSecret       = $config['consumerSecret'];
        $redirectUrl        = $config['callbackUrl'];
        $responseType       = $config['responseType'];

        try {
            if (isset($_GET['code']) && !empty($_GET['code'])) {

                $options = array(
                    'client_id'     => $clientId,
                    'redirect_uri'  => $redirectUrl,
                    'client_secret' => $clientSecret,
                    'code'          => trim($_GET['code']),
                    'grant_type'    => 'authorization_code',
                );

                $accessTokenInfo = $consumer->getAccessToken($options);
                if (!array_key_exists('access_token', $accessTokenInfo)) {
                    throw new \Exception('Access Token Error');
                }
                $accessToken = $accessTokenInfo['access_token'];

                $options = array(
                    'oauth_token' => $accessToken,
                    'format' => 'json'
                );

                $identity = $consumer->getIdentity($options);
                $identity['_ConsumerName'] = $this->_consumerName;

                return new \Oauth2\AuthResult(\Oauth2\AuthResult::SUCCESS, new \Oauth2\Identity($identity));

            } elseif (!isset($_GET['error'])) {

                $consumer->redirect(array(
                    'client_id' => $this->_config['consumerId'],
                    'redirect_uri' => $this->_config['callbackUrl'],
                    'response_type' => 'code',
                    'scope' => 'offline'
                ));

            } else {
                throw new Exception($_GET['error']);
            }

        } catch (Exception $e) {
            return new \Oauth2\AuthResult(\Oauth2\AuthResult::FAILURE, false, array($e->getMessage()));
        }
    }

}