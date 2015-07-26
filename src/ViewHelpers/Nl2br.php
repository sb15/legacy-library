<?php

class Zend_View_Helper_Nl2br extends Zend_View_Helper_Abstract {
	
	public function nl2br($text) {
		return str_replace("\n","<br/>", $text);
	}
}