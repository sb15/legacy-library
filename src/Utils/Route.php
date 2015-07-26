<?php

class Utils_Route  
{
    
    public static function initRouterFromConfigIni(&$router, $configFile, $defaults = array())
    {
        $router->removeDefaultRoutes();
        
        $config = new Zend_Config_Ini($configFile);
        $routes = $config->toArray();
        
        $commonDefaultParams = array();
        if (array_key_exists('commonDefaults', $routes)) {
            $commonDefaultParams = $routes['commonDefaults'];
            unset($routes['commonDefaults']);
        }
        
        foreach ($routes as $routeName => &$route) {
        
            if (count($commonDefaultParams)) {
                $route['defaults'] = array_merge(@$route['defaults'], $commonDefaultParams);
            }
        
            foreach ($route['defaults'] as $routeDefault => &$routeDefaultValue) {
        
                if (strpos($routeDefaultValue, "$") === 0) {
                    $routeDefaultVariable = ltrim($routeDefaultValue, "$");
                    if (array_key_exists($routeDefaultVariable, $defaults)) {
                        $routeDefaultValue = $defaults[$routeDefaultVariable];
                    }
                }
            }
        
            $currentRouter = array_key_exists('router', $route) ? $route['router'] : false;
            if (!$currentRouter) {
        
                if (strpos($route['route'], ":") === false) {
                    $currentRouter = 'Zend_Controller_Router_Route_Static';
                } else {
                    $currentRouter = 'Zend_Controller_Router_Route';
                }
                if (!$currentRouter) {
                    throw new Exception('Router not defined');
                }
            }
            
            // $reqs = array(), Zend_Translate $translator = null, $locale = null,
            if (!array_key_exists('reqs', $route)) {
                $route['reqs'] = array();               
            }
            
            $currentRoute  = new $currentRouter($route['route'], $route['defaults'], $route['reqs']);
        
            $router->addRoute($routeName, $currentRoute);
        }        
    }
    
}