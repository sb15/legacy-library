<?php

namespace Oauth2\Adapter;

class Mailru implements \Oauth2\Adapter\CommonInterface
{
    protected $_consumerName = 'mailru';

    protected $_config = array(
        'consumerId'            => '',
        'consumerSecret'        => '',

        'userAuthorizationUrl'  => 'https://connect.mail.ru/oauth/authorize',
        'accessTokenUrl'        => 'https://connect.mail.ru/oauth/token',
        'requestDataUrl'        => 'http://www.appsmail.ru/platform/api',
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
                    'app_id' => $config['consumerId'],
                    'method' => 'users.getInfo',
                    'secure' => 1,
                    'session_key' => $accessToken,
                );
                $sig = $this->getSign($options, $accessToken);
                $options['sig'] = $sig;

                $identity = $consumer->getIdentity($options);
                $identity = (array) $identity[0];
                $identity['_ConsumerName'] = $this->_consumerName;

                return new \Oauth2\AuthResult(\Oauth2\AuthResult::SUCCESS, new \Oauth2\Identity($identity));

            } elseif (!isset($_GET['error'])) {

                $consumer->redirect(array(
                    'client_id' => $this->_config['consumerId'],
                    'redirect_uri' => $this->_config['callbackUrl'],
                    'response_type' => 'code'
                ));

            } else {
                throw new Exception($_GET['error']);
            }

        } catch (Exception $e) {
            return new \Oauth2\AuthResult(\Oauth2\AuthResult::FAILURE, false, array($e->getMessage()));
        }
    }
    
    /**
     * Return mail.ru sign
     * @param array $requestParams Request parameters
     * @return string Signature
     */
    public function getSign(array $requestParams, $accessToken) {
        
        $config = $this->_config;

        $consumerSecret = $config['consumerKey'];
        ksort($requestParams);

        $params = '';
        foreach ($requestParams as $key => $value) {
            $params .= $key . '=' . $value;
        }
        return md5($params . $consumerSecret);
    }    
}