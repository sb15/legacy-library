<?php

class CoreAutoloader
{

    public static function init($dirs = array(), $namespaces = array())
    {
        $commonDirs = array();

        $commonDirs[] = dirname(__FILE__);
        $commonDirs[] = realpath(dirname(dirname(__FILE__)) . '/_library');
		
        $commonNamespaces = array();

        $loader = new \Phalcon\Loader();
        $loader->registerDirs(
            array_merge($commonDirs, $dirs)
        );

        $loader->registerNamespaces(
            array_merge($commonNamespaces, $namespaces)
        );

        $loader->register();
    }

}
