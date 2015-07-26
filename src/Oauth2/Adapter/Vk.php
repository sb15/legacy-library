<?php

namespace Oauth2\Adapter;

class Vk implements \Oauth2\Adapter\CommonInterface
{
    protected $_consumerName = 'vk';

    protected $_config = array(
        'consumerId'            => '',
        'consumerSecret'        => '',
        'callbackUrl'           => '',

        'userAuthorizationUrl'  => 'http://api.vk.com/oauth/authorize',
        'accessTokenUrl'        => 'https://api.vk.com/oauth/access_token',
        'requestDataUrl'       => 'https://api.vk.com/method/getProfiles',
        'responseType'          => 'code',
        'scope'                 => array(),
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
                    'client_secret' => $clientSecret,
                    'code'          => trim($_GET['code']),
                    'redirect_uri'  => $this->_config['callbackUrl']
                );

                $accessTokenInfo = $consumer->getAccessToken($options);
                if (!array_key_exists('access_token', $accessTokenInfo)) {
                    throw new \Exception('Access Token Error');
                }

                $accessToken = $accessTokenInfo['access_token'];

                $options = array(
                    'uid'     => $accessTokenInfo['user_id'],
                    'fields' => implode($this->_config['fields'], ','),
                    'access_token'  => $accessToken,
                );

                $identity = $consumer->getIdentity($options);
                $identity = (array) $identity['response']['0'];
                $identity['_ConsumerName'] = $this->_consumerName;



                return new \Oauth2\AuthResult(\Oauth2\AuthResult::SUCCESS, new \Oauth2\Identity($identity));



            } elseif (!isset($_GET['error'])) {

                $consumer->redirect(array(
                    'client_id' => $this->_config['consumerId'],
                    'redirect_uri' => $this->_config['callbackUrl'],
                    'response_type' => 'code',
                    'scope'         => implode($this->_config['scope'], ' ')
                ));

            } else {
                throw new Exception($_GET['error']);
            }

        } catch (Exception $e) {
            return new \Oauth2\AuthResult(\Oauth2\AuthResult::FAILURE, false, array($e->getMessage()));
        }
    }
    
}