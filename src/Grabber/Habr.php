<?php

require_once 'Grabber/Abstract.php';

class Grabber_Habr extends Grabber_Abstract {
	
	public function grabWeb() 
	{
		
	}
	
	public function getPosts() {
		$url = "http://habrahabr.ru";
		$content = file_get_contents($url);	
		
		$messages = array();
		if (preg_match_all('#<a[^>]*class="blog"[^>]*>(.*?)</a>.*?<a[^>]*href="([^"]*)"[^>]*class="topic"[^>]*>(.*?)</a>#uis', $content, $m)) {
			foreach ($m[0] as $k => $v) {
				$message = $m[1][$k] . ' - '.$m[3][$k] .' '. $m[2][$k];
				//$messages[] = substr($message, 0, 140);
				$messages[] = strip_tags($message);
			}
		}
		
		return $messages;	
	}
	
	public function getLastPosts() 
	{
		$urlFile = '/home/i6eru/tmp/Grabber_Habr_Last';
		$lastPostHash = null;
		if (is_file($urlFile)) {
			$lastPostHash = file_get_contents($urlFile);
		}
		$posts = $this->getPosts();
		$result = array();	
		foreach ($posts as $post) {
			if (sha1($post) != $lastPostHash) {
				$result[] = $post;
			} else {
				break;
			}
		}
		
		$lastPostHash = sha1($posts[0]);
		file_put_contents($urlFile, $lastPostHash);
		
		return array_reverse($result);
	}
	
}