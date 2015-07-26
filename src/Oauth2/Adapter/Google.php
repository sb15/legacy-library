<?php

namespace Oauth2\Adapter;

class Google implements \Oauth2\Adapter\CommonInterface
{

    protected $_consumerName = 'google';

    protected $_config = array(
        'consumerId'            => '',
        'consumerSecret'        => '',
        'callbackUrl'           => '',

        'userAuthorizationUrl'  => 'https://accounts.google.com/o/oauth2/auth',
        'accessTokenUrl'        => 'https://accounts.google.com/o/oauth2/token',
        'requestDataUrl'        => 'https://www.googleapis.com/oauth2/v1/userinfo',
        'responseType'          => 'code',
        'scope'                 => null
    );

    public function __construct($options)
    {
        $this->_config = array_merge($this->_config, $options);
    }

    public function authenticate()
    {
        $consumer = new \Oauth2\Consumer($this->_config);

        try {
            if (isset($_GET['code']) && !empty($_GET['code'])) {

                $options = array(
                    'client_id'     => $this->_config['consumerId'],
                    'client_secret' => $this->_config['consumerSecret'],
                    'redirect_uri'  => $this->_config['callbackUrl'],
                    'code'          => trim($_GET['code']),
                    'grant_type'    => 'authorization_code'
                );
                $accessTokenInfo = $consumer->getAccessToken($options);
                if (!array_key_exists('access_token', $accessTokenInfo)) {
                    throw new \Exception('Access Token Error');
                }
                $accessToken = $accessTokenInfo['access_token'];

                $options = array(
                    'access_token' => $accessToken
                );

                $identity = $consumer->getIdentity($options);
                $identity['_ConsumerName'] = $this->_consumerName;

                return new \Oauth2\AuthResult(\Oauth2\AuthResult::SUCCESS, new \Oauth2\Identity($identity));

            } elseif (!isset($_GET['error'])) {

                $consumer->redirect(array(
                    'client_id' => $this->_config['consumerId'],
                    'redirect_uri' => $this->_config['callbackUrl'],
                    'response_type' => 'code',
                    'state'         => 'profile',
                    'access_type'   => 'offline',
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
