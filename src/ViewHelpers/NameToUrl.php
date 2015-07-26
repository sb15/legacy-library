<?php

class Zend_View_Helper_NameToUrl extends Zend_View_Helper_Abstract {

    public function nameToUrl($name)
    {
        return Utils_Url::filterNameForUrl($name);
    }

}