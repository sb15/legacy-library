<?php

namespace PhalconEx\Helpers;

class UrlHelper
{

    private $di = null;

    public function __construct($di)
    {
        $this->di = $di;
    }


    public function get($routeName, $routeParams = array())
    {
        $url = $this->di->get('url');

        $options = array(
            'for' => $routeName
        );

        $options = array_merge($options, $routeParams);

        //$url->setBaseUri('http://fsdfsd.ru/');

        return $url->get($options);
    }

}