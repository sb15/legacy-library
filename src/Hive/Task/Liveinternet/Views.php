<?php

namespace Hive\Task\Liveinternet;

class Views extends \Hive\Task\Liveinternet\LiveinternetAbstract
{

    public function getStartUrl()
    {
        return 'http://www.liveinternet.ru/stat/' . urlencode($this->getOption('username')) . '/index.html';
    }


    public function grabItem()
    {
        $dom = $this->getBrowser()->getDomParserNokogiri();
        $tableRow = $dom->get('table[bgcolor=#e8e8e8] tr')->toArray();		
		
        $result = array();

        $views = null;
        $viewsChange = null;
        $visitors = null;
        $visitorsChange = null;

        if (is_array($tableRow['1']['td']['2']['#text']) && array_key_exists('0', $tableRow['1']['td']['2']['#text'])) {
			$views = preg_replace("#[^\d]+#uis", "", $tableRow['1']['td']['2']['#text']['0']);
			$viewsChange = preg_replace("#[^-\d]+#uis", "", $tableRow['1']['td']['2']['a']['0']['font']['0']['#text']);			
        } elseif (isset($tableRow['1']['td']['2']['#text'])) {
			$views = preg_replace("#[^\d]+#uis", "", $tableRow['1']['td']['2']['#text']);
			$viewsChange = 0;
		}

        if (is_array($tableRow['3']['td']['2']['#text']) && array_key_exists('0', $tableRow['3']['td']['2']['#text'])) {
            $visitors = preg_replace("#[^\d]+#uis", "", $tableRow['3']['td']['2']['#text']['0']);
            $visitorsChange = preg_replace("#[^-\d]+#uis", "", $tableRow['3']['td']['2']['a']['0']['font']['0']['#text']);
        } elseif (isset($tableRow['3']['td']['2']['#text'])) {
			$visitors = preg_replace("#[^\d]+#uis", "", $tableRow['3']['td']['2']['#text']);
            $visitorsChange = 0;
		}		

        $result[] = array(
            'views' => $views,
            'views_change' => $viewsChange,
            'visitors' => $visitors,
            'visitors_change' => $visitorsChange
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
            'views' => $data['views'],
            'views_change' => $data['views_change'],
            'visitors' => $data['visitors'],
            'visitors_change' => $data['visitors_change']
        );
    }

    public function getStrategy()
    {
        return 'update';
    }

    public function getDestinationUpdate()
    {
        return "project_id = " . $this->getOption('project_id');
    }

}
