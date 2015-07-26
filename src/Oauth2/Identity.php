<?php

namespace Oauth2;


class Identity
{

    private $identity = array();

    public function __construct($identity)
    {
        $consumerUserId = null;
        $consumerUserName = null;
        $consumerUserEmail = null;
        $consumerUserImage = null;

        switch ($identity['_ConsumerName']) {
            case 'google':
                $consumerUserId = $identity['id'];
                $consumerUserName = $identity['name'];
                $consumerUserEmail = $identity['email'];
                $consumerUserImage = $identity['picture'];
                break;
            case 'vk':
                $consumerUserId = $identity['uid'];
                $consumerUserName = $identity['first_name'] . ' ' . $identity['last_name'];
                $consumerUserImage = $identity['photo_rec'];
                break;
            case 'mailru':
                $consumerUserId = $identity['uid'];
                $consumerUserName = $identity['first_name'] . ' ' . $identity['last_name'];
                $consumerUserEmail = $identity['email'];
                break;
            case 'yandex':
                $consumerUserId = $identity['id'];
                $consumerUserName = $identity['real_name'];
                $consumerUserEmail = $identity['default_email'];
                break;
        }

        $identity['_ConsumerUserId'] = $consumerUserId;
        $identity['_ConsumerUserName'] = $consumerUserName;
        $identity['_ConsumerUserEmail'] = $consumerUserEmail;
        $identity['_ConsumerUserImage'] = $consumerUserImage;

        $this->identity = $identity;
    }

    public function getConsumerName()
    {
        return $this->identity['_ConsumerName'];
    }

    public function getConsumerUserId()
    {
        return $this->identity['_ConsumerUserId'];
    }

    public function getConsumerUserName()
    {
        return $this->identity['_ConsumerUserName'];
    }

    public function getConsumerUserEmail()
    {
        return $this->identity['_ConsumerUserEmail'];
    }

    public function getConsumerUserImage()
    {
        return $this->identity['_ConsumerUserImage'];
    }

}