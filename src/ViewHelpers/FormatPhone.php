<?php

class Zend_View_Helper_FormatPhone extends Zend_View_Helper_Abstract {

    public function formatPhone($phone) {
        $phone = preg_replace('|[^0-9]|is','',$phone);
        $phone = '+' . substr($phone,0,1)." (".substr($phone,1,3).") ".substr($phone,4,3)."-".substr($phone,7,2)."-".substr($phone,9,2);
        return $phone;
    }
}