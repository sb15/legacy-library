<?php

namespace Optimization\Assets;

class JS extends AbstractAssets
{
    protected $type = 'javascripts';
    protected $ext = 'js';

    public function javascripts($group, $theme = 'default')
    {
        return $this->assets($group, $theme);
    }

    protected function getCodeString($file)
    {
        return '<script src="' . $file . '"></script>' . PHP_EOL;
    }
}
