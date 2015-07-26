<?php

namespace Oauth2;

class AuthResult {

    const SUCCESS = 1;
    const FAILURE = 0;

    protected $_code;
    protected $_identity;
    protected $_messages;

    public function __construct($code, $identity, array $messages = array())
    {

        $code = (int) $code;
        $this->_code     = $code;
        $this->_identity = $identity;
        $this->_messages = $messages;
    }

    public function isValid()
    {
        return ($this->_code > 0) ? true : false;
    }

    /**
     * @return \Oauth2\Identity
     */
    public function getIdentity()
    {
        return $this->_identity;
    }

    public function getMessages()
    {
        return $this->_messages;
    }

}