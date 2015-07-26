<?php

require_once 'Grabber/Abstract.php';

class Grabber_Google extends Grabber_Abstract {

	public function grabWeb($query)
	{

		$params = array();
		$params['callback'] = 'google.search.WebSearch.RawCompletion';
		$params['context'] = '0';
		$params['lstkp'] = '0';
		$params['rsz'] = 'small';
		$params['hl'] = 'ru';
		$params['source'] = 'gsc';
		$params['gss'] = '.com';
		$params['sig'] = '457a1b12dfe20ca00fd65f9ad5d52ccd';
		$params['q'] = urlencode($query);
		$params['gl'] = 'www.google.com';
		$params['qid'] = '126b3eb7dcc25e0a8';
		$params['key'] = 'ABQIAAAA1XbMiDxx_BTCY2_FkPh06RRaGTYH6UMl8mADNa0YKuWNNa8VNxQEerTAUcfkyrr6OwBovxn7TDAH5Q';
		$params['v'] = '1.0';


		$url = "http://www.google.com/uds/GwebSearch?" . $this->implodeArray($params, '&');

		$a = $this->getFromCache($url);

		echo $a;

		require_once 'Zend/Json.php';
		echo $url."<br><br><br>";
		echo "<pre>";

//		$a = 'google.search.WebSearch.RawCompletion(\'0\',{"results":[{"GsearchResultClass":"GwebSearch","unescapedUrl":"http://salevrn.ru/2009/09/28/set-torgovyx-centrov-poisk-podarki/","url":"http://salevrn.ru/2009/09/28/set-torgovyx-centrov-poisk-podarki/","visibleUrl":"salevrn.ru","cacheUrl":"http://www.google.com/search?q\u003dcache:rqevA6C-FbAJ:salevrn.ru","title":"РЎРµС‚СЊ С‚РѕСЂРіРѕРІС‹С… С†РµРЅС‚СЂРѕРІ вЂњ\u003cb\u003eРџРћР�РЎРљ\u003c/b\u003eвЂќ. РџРѕРґР°СЂРєРё | В«Salevrn.ruВ» вЂ“ РЎРєРёРґРєРё \u003cb\u003eРІ\u003c/b\u003e \u003cb\u003e...\u003c/b\u003e","titleNoFormatting":"РЎРµС‚СЊ С‚РѕСЂРіРѕРІС‹С… С†РµРЅС‚СЂРѕРІ вЂњРџРћР�РЎРљвЂќ. РџРѕРґР°СЂРєРё | В«Salevrn.ruВ» вЂ“ РЎРєРёРґРєРё РІ ...","content":"28 СЃРµРЅ 2009 \u003cb\u003e...\u003c/b\u003e С‚С† \u003cb\u003eРїРѕРёСЃРє РІРѕСЂРѕРЅРµР¶\u003c/b\u003e [...] РЎРѕР·РґР°РЅРёРµ СЃР°Р№С‚РѕРІ В«WebrainВ» - РїСЂРµРґРѕСЃС‚Р°РІР»СЏРµС‚ СЃРєРёРґРєРё | В«  Salevrn.ruВ» - РЎРєРёРґРєРё \u003cb\u003eРІ Р’РѕСЂРѕРЅРµР¶Рµ\u003c/b\u003e | РћРєС‚СЏР±СЂСЊ 2, 2009РІ 09:45 \u003cb\u003e...\u003c/b\u003e"},{"GsearchResultClass":"GwebSearch","unescapedUrl":"http://job.ukr.net/city/voronezh/","url":"http://job.ukr.net/city/voronezh/","visibleUrl":"job.ukr.net","cacheUrl":"http://www.google.com/search?q\u003dcache:Q-jqU7sOiYkJ:job.ukr.net","title":"Р Р°Р±РѕС‚Р° \u003cb\u003eРІ Р’РѕСЂРѕРЅРµР¶Рµ\u003c/b\u003e, РІР°РєР°РЅСЃРёРё, РёС‰Сѓ СЂР°Р±РѕС‚Сѓ \u003cb\u003eР’РѕСЂРѕРЅРµР¶\u003c/b\u003e, \u003cb\u003eРїРѕРёСЃРє\u003c/b\u003e СЂР°Р±РѕС‚С‹ Рё \u003cb\u003e...\u003c/b\u003e","titleNoFormatting":"Р Р°Р±РѕС‚Р° РІ Р’РѕСЂРѕРЅРµР¶Рµ, РІР°РєР°РЅСЃРёРё, РёС‰Сѓ СЂР°Р±РѕС‚Сѓ Р’РѕСЂРѕРЅРµР¶, РїРѕРёСЃРє СЂР°Р±РѕС‚С‹ Рё ...","content":"Р Р°Р±РѕС‚Р° \u003cb\u003eРІ Р’РѕСЂРѕРЅРµР¶Рµ\u003c/b\u003e, Р·Р°СЂРїР»Р°С‚Р°: 4294967295 РіСЂРЅ. РђРєС‚СѓР°Р»СЊРЅС‹Рµ РІР°РєР°РЅСЃРёРё, \u003cb\u003eРїРѕРёСЃРє\u003c/b\u003e СЂР°Р±РѕС‚С‹ \u003cb\u003eРІ\u003c/b\u003e   \u003cb\u003eР’РѕСЂРѕРЅРµР¶Рµ\u003c/b\u003e РїРѕ РІСЃРµРј СЃРїРµС†РёР°Р»СЊРЅРѕСЃС‚СЏРј. Р�С‰РёС‚Рµ СЂР°Р±РѕС‚Сѓ РЅР° JOB.ukr.net."},{"GsearchResultClass":"GwebSearch","unescapedUrl":"http://voronezh.hh.ru/","url":"http://voronezh.hh.ru/","visibleUrl":"voronezh.hh.ru","cacheUrl":"http://www.google.com/search?q\u003dcache:RrvIh35I5owJ:voronezh.hh.ru","title":"Р Р°Р±РѕС‚Р° \u003cb\u003eРІ Р’РѕСЂРѕРЅРµР¶Рµ\u003c/b\u003e, \u003cb\u003eРїРѕРёСЃРє\u003c/b\u003e СЂР°Р±РѕС‚С‹ \u003cb\u003eРІ Р’РѕСЂРѕРЅРµР¶Рµ\u003c/b\u003e, РІР°РєР°РЅСЃРёРё \u003cb\u003eР’РѕСЂРѕРЅРµР¶Р°\u003c/b\u003e \u003cb\u003e...\u003c/b\u003e","titleNoFormatting":"Р Р°Р±РѕС‚Р° РІ Р’РѕСЂРѕРЅРµР¶Рµ, РїРѕРёСЃРє СЂР°Р±РѕС‚С‹ РІ Р’РѕСЂРѕРЅРµР¶Рµ, РІР°РєР°РЅСЃРёРё Р’РѕСЂРѕРЅРµР¶Р° ...","content":"HeadHunter (hh.ru) РїРѕР·РІРѕР»СЏРµС‚ РЅР°Р№С‚Рё СЂР°Р±РѕС‚Сѓ РІ РњРѕСЃРєРІРµ, СЂРµРіРёРѕРЅР°С… Рё Р·Р° СЂСѓР±РµР¶РѕРј.   HeadHunter - СЌС‚Рѕ РєР°С‡РµСЃС‚РІРµРЅРЅР°СЏ Р±Р°Р·Р° СЂРµР·СЋРјРµ Рё РІР°РєР°РЅСЃРёР№ Рё Р»СѓС‡С€РёРµ СЃРµСЂРІРёСЃС‹ РґР»СЏ \u003cb\u003eРїРѕРёСЃРєР°\u003c/b\u003e   \u003cb\u003e...\u003c/b\u003e"},{"GsearchResultClass":"GwebSearch","unescapedUrl":"http://www.nmarket.ru/phone/search/","url":"http://www.nmarket.ru/phone/search/","visibleUrl":"www.nmarket.ru","cacheUrl":"http://www.google.com/search?q\u003dcache:YN2qn3UMl5QJ:www.nmarket.ru","title":"В© NMarket.Ru - РўРµР»РµС„РѕРЅРЅС‹Р№ СЃРїСЂР°РІРѕС‡РЅРёРє \u003cb\u003eР’РѕСЂРѕРЅРµР¶Р°\u003c/b\u003e. On-Line \u003cb\u003eРїРѕРёСЃРє\u003c/b\u003e. Р‘Р°Р·Р° \u003cb\u003e...\u003c/b\u003e","titleNoFormatting":"В© NMarket.Ru - РўРµР»РµС„РѕРЅРЅС‹Р№ СЃРїСЂР°РІРѕС‡РЅРёРє Р’РѕСЂРѕРЅРµР¶Р°. On-Line РїРѕРёСЃРє. Р‘Р°Р·Р° ...","content":"РўРµР»РµС„РѕРЅРЅС‹Р№ СЃРїСЂР°РІРѕС‡РЅРёРє \u003cb\u003eР’РѕСЂРѕРЅРµР¶Р°\u003c/b\u003e. РљРІР°СЂС‚РёСЂРЅС‹Рµ С‚РµР»РµС„РѕРЅС‹, С‚РµР»РµС„РѕРЅС‹ РѕСЂРіР°РЅРёР·Р°С†РёР№   \u003cb\u003eР’РѕСЂРѕРЅРµР¶Р°\u003c/b\u003e. Р‘Р°Р·Р° РґР°РЅРЅС‹С… Р¶РёС‚РµР»РµР№ \u003cb\u003eР’РѕСЂРѕРЅРµР¶Р°\u003c/b\u003e."}],"cursor":{"pages":[{"start":"0","label":1},{"start":"4","label":2},{"start":"8","label":3},{"start":"12","label":4},{"start":"16","label":5},{"start":"20","label":6},{"start":"24","label":7},{"start":"28","label":8}],"estimatedResultCount":"418000","currentPageIndex":0,"moreResultsUrl":"http://www.google.com/search?oe\u003dutf8\u0026ie\u003dutf8\u0026source\u003duds\u0026start\u003d0\u0026hl\u003dru\u0026q\u003d%D0%92%D0%BE%D1%80%D0%BE%D0%BD%D0%B5%D0%B6+%D0%BF%D0%BE%D0%B8%D1%81%D0%BA"}}, 200, null, 200)';

		if (preg_match('#{.*}#is', $a, $m)) {
			$b = Zend_Json::decode($m[0]);
			print_r($b);
		}



	}

	/**
	 *
	 * http://api-maps.yandex.ru/1.1.21/xml/Geocoder/Geocoder.xml?key=AFKqbE0BAAAAWyKbfgIAP3WQ752MmJjCSU4whyLS-Ik7-dYAAAAAAAAAAADvD_BB3GK7scwIUq9-CBaOtMjgjQ==&geocode=%D0%92%D0%BE%D1%80%D0%BE%D0%BD%D0%B5%D0%B6%20%D0%9B%D0%B5%D0%BD%D0%B8%D0%BD%D1%81%D0%BA%D0%B8%D0%B9%20%D0%BF%D1%80%D0%BE%D1%81%D0%BF%D0%B5%D0%BA%D1%82%2C%20215&ll=39.204078%2C51.662507&spn=0.30899%2C0.064054&results=1&callback=jsonp1332271363743
	 *
	 */


}