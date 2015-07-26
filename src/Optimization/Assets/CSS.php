<?php

namespace Optimization\Assets;

class CSS extends AbstractAssets
{
    protected $type = 'stylesheets';
    protected $ext = 'css';

    public function stylesheets($group, $theme = 'default')
    {	
        return $this->assets($group, $theme);
    }

    protected function getCodeString($file)
    {
        return '<link rel="stylesheet" href="' . $file . '" media="screen" />' . PHP_EOL;
    }

    protected function isUseCompressed()
    {
        return parent::isUseCompressed() || stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE');
    }
}
