<?php

class Zend_View_Helper_PrimaryServerUrl extends Zend_View_Helper_Abstract {

    public function primaryServerUrl($path, $subdomain = '') {
        if (!empty($subdomain)) {
            $subdomain = $subdomain . '.';
        }
        return 'http://' . $subdomain . PRIMARY_DOMAIN . $path;
    }

}