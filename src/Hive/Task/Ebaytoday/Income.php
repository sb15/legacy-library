<?php

namespace Hive\Task\Ebaytoday;

class Income extends \Hive\Task\AbstractTask
{

    //private $urls = array('http://shopotam.ru/user/partner');

    public function getStartUrl()
    {
        return 'http://shopotam.ru/user/partner';
    }

    public function login()
    {
        $form = array();
        $form['action'] = 'http://shopotam.ru/auth/signin';
        $form['method'] = 'POST';
        $form['fields'] = array();
        $form['enctype'] = \Zend_Http_Client::ENC_URLENCODED;
        $form['fields']['login'] = $this->getOption('username');
        $form['fields']['pass'] = $this->getOption('password');
        $form['fields']['referer'] = '/';
        $form['fields']['remember'] = 'on';
        $this->getBrowser()->sentForm($form);
    }

    public function grabList()
    {
        return array(
            'item' => $this->grabItem()
        );
    }

    public function grabItem()
    {
        $dom = $this->getBrowser()->getDomParserNokogiri();
        $balance = 0;

        $usaBalance = $dom->get('.right-block')->get('.item')->toArray();
		$balance = preg_replace("#[^\d\.,]+#uis", "",$usaBalance['0']['#text']['2']);
		
		$creditBalanceData = $dom->get('.userpanel .balance .item')->toArray();
		$creditBalance = preg_replace("#[^\d\.,]+#uis", "", $creditBalanceData['4']['#text']['1']);
		
		$balance -= $creditBalance;

        $users = 0;
        $usersChange = 0;

        $levelOneUsers = $dom->get('.partners-in-one-day-levelOne')->toArray();
        foreach ($levelOneUsers as $elem) {
            $usersChange += intval(trim($elem['#text']));
        }

        $levelTwoUsers = $dom->get('.partners-in-one-day-levelTwo')->toArray();
        foreach ($levelTwoUsers as $elem) {
            $usersChange += intval(trim($elem['#text']));
        }

        $allUserLevelOne = $dom->get('.all-first-level')->toArray();
        $allUserLevelOne = intval(trim($allUserLevelOne['0']['#text']));

        $allUserLevelTwo = $dom->get('.all-second-level')->toArray();
        $allUserLevelTwo = intval(trim($allUserLevelTwo['0']['#text']));

        $users += $allUserLevelOne + $allUserLevelTwo - $usersChange;

        $result = array();
        $result[] = array(
            'balance' => $balance,
            'users' => $users,
            'users_change' => $usersChange
        );

        return $result;
    }

    public function getNextPage($content, $currentPage = 1)
    {
        return false;
    }

    public function getDestinationOptions($data = array())
    {
        return array(
            'project_id' => $this->getOption('project_id'),
            'update_time' => date('Y-m-d H:i:s'),
            'balance' => $data['balance'],
            'users' => $data['users'],
            'users_change' => $data['users_change']
        );
    }

    public function getStrategy()
    {
        return 'update';
    }

    public function getDestinationUpdate()
    {
        return "project_id = '" . $this->getOption('project_id') . "'";
    }

}
