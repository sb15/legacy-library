<?php

require_once 'Zend/Dom/Nokogiri.php';
require_once 'Zend/Debug.php';

abstract class Grabber_Abstract {

	///^([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/i

    protected $_storage = null;

    public function setStorage($storage)
    {
        if ($storage instanceof Grabber_Storage_Interface) {
            $this->_storage = $storage;
        }
    }

    public function getStorage()
    {
        if (is_null($this->_storage)) {
            throw new Exception('No starage');
        }
        return $this->_storage;
    }

    // @todo callback support

	public function implodeArray($arr, $delimeter) {
		$result = '';
		foreach ($arr as $k => $v) {
			$result .= "{$delimeter}{$k}={$v}";
		}
		return $result;
	}

	public function getFromCache($url) {
		$urlHash = sha1($url);
		//$urlFile = realpath(APPLICATION_PATH ."/../data"). '/' .$urlHash;
		$urlFile = realpath(sys_get_temp_dir()) . '/' . $urlHash;
		echo $urlFile;
		if (is_file($urlFile)) {
			echo 'cache:';
			return file_get_contents($urlFile);
		} else {
			$temp = file_get_contents($url);
			file_put_contents($urlFile, $temp);
			return $temp;
		}
	}

	public function getContent($url, $encoding = 'UTF-8')
	{
	    // сделать кеш
	    $result = file_get_contents($url);
	    if ($encoding != 'UTF-8') {
	        $result = iconv($encoding, 'UTF-8', $result);
	        //$result = preg_replace('/<(script|style|noscript)\b[^>]*>.*?<\/\1\b[^>]*>/is', '', $result);
	        //$result = preg_replace('#<meta[^>]+>#isu', '', $result);
	        $result = preg_replace('#<head\b[^>]*>#isu', "<head>\r\n<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />", $result);
	    }
	    return $result;
	}

	public function getNokogiri($content)
	{
	    return new Zend_Dom_Nokogiri($content);
	}

	public function getElementValue($element, $key = '#text')
	{
	    if (!is_array($element)) {
	        $element = $element->toArray();
	    }
	    $element = reset($element);
	    if (is_array($element) && array_key_exists($key, $element)) {
	        return $element[$key];
	    }
	    return null;
	}

	public function getNextPage($content, $currentPage = 1)
	{
	    $nextPage = $currentPage + 1; // strategy add

	    $saw = $this->getNokogiri($content);
	    $links = $saw->get('a')->toArray();
	    foreach ($links as $link) {
	        if (isset($link['#text']) && $link['#text'] == $nextPage) {
	            return $link['href'];
	        } elseif (preg_match("#" . $nextPage . "\.html#is", $link['href'])) {
	            return $link['href'];
	        }
	    }
        return false;
	}

	abstract public function grabList($content, $url = null);
	abstract public function grabItem($url, $content);

	public function isAbsoluteUrl($url)
	{
	    // exception @todo
	    if (preg_match("#^http:#is", $url)) {
	        return true;
	    } else {
	        return false;
	    }
	}

	public function startGrabFromUrl($url, $encoding)
	{
	    $nextPageUrl = $url;
	    $page = 1;

	    $urlParts = parse_url($url);
	    $relativeUrl = dirname($url);

	    do {

	        $content = $this->getContent($nextPageUrl, $encoding);
	        // @todo detect encoding

	        $currentUrl = $nextPageUrl;
	        $nextPageUrl = $this->getNextPage($content, $page);

	        if ($nextPageUrl && !$this->isAbsoluteUrl($nextPageUrl)) {
	            $nextPageUrl = $relativeUrl . '/' . $nextPageUrl;
	        }



	        $page++;

	        $listUrl = $this->grabList($content, $currentUrl);

	        //echo '<pre>'; print_r($listUrl); echo '</pre>';

	        //Zend_Debug::dump($nextPageUrl);
	        //Zend_Debug::dump($listUrl);

	        foreach ($listUrl as $url) {
	            $content = $this->getContent($url, $encoding);
	            $item = $this->grabItem($url, $content);
	            $this->getStorage()->save($item);

	        }

	    } while ($nextPageUrl !== false);
	}

}