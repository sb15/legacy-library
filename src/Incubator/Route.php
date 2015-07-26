<?php

namespace Incubator;

class Route
{

    private static $routes = array();

    public static function addRoute($route, &$parent)
    {

        $routeElement = array();
        $routeElement['type'] = 'Zend\\Mvc\\Router\\Http\\' . $route['type'];
        $routeElement['options'] = array();
        $routeElement['options']['route'] = $route['route'];
        $routeElement['options']['defaults'] = array();
        $routeElement['options']['defaults']['controller'] = $route['controller'];
        $routeElement['options']['defaults']['action'] = $route['action'];

        if (array_key_exists('defaults', $route)) {
            $routeElement['options']['defaults'] = array_merge($routeElement['options']['defaults'], $route['defaults']);
        }

        if (array_key_exists('constraints', $route)) {
            $routeElement['options']['constraints'] = $route['constraints'];
        }

        $routeElement['may_terminate'] = true;

        /*if (!isset($route['controller'])) {
            echo '<pre>'; var_dump($parent); echo '</pre>';
            echo '<pre>'; print_r($route); echo '</pre>';die;
        }*/

        if (array_key_exists('childs', $route)) {
            foreach ($route['childs'] as $routeName => $childRoute) {
                self::addRoute($childRoute, $routeElement['child_routes'][$routeName]);
            }
        }

        $parent = $routeElement;

    }

    public static function fromYml($fileName)
    {
        self::$routes = array();
        $routes = \Spyc\Spyc::YAMLLoad($fileName);

        foreach ($routes as $routeName => $route) {
            self::addRoute($route, self::$routes[$routeName]);
        }

        return self::$routes;
    }
}