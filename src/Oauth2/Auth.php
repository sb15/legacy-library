<?php

namespace Oauth2;

class Auth {


    /**
     * @return \Oauth2\AuthResult
     */
    public function authenticate($adapter)
    {
        return $adapter->authenticate();
    }

}
 