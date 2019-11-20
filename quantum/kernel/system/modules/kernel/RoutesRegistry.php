<?php


namespace Quantum;


/**
 * Class RoutesRegistry
 * @package Quantum
 */
class RoutesRegistry extends Singleton
{
    /**
     * @var Router
     */
    public $router;

    /**
     * RoutesRegistry constructor.
     */
    function __construct()
    {
        $this->router = new Router();
    }

    /**
     * @param ValueTree $routes
     */
    public static function addRoutes(ValueTree $routes)
    {
        if ($routes->isEmpty())
            return;

        foreach ($routes->all() as $route)
        {
            self::addControllerRoute($route->getUri(), $route->getController(), $route->getMethod());
        }
    }

    /**
     * @param $uri
     * @param $controllerName
     * @param $controllerAction
     */
    public static function addControllerRoute($uri, $controllerName, $controllerAction)
    {
        $router = self::getInstance()->router;
        $router->addControllerRoute($uri, $controllerName, $controllerAction);
    }

    /**
     * @param $uri
     * @return mixed
     */
    public static function getControllerRoute($uri)
    {
        $router = self::getInstance()->router;
        $route = $router->getControllerRoute($uri);

        return $route;
    }

    /**
     * @return mixed
     */
    public static function createInstance()
    {
        return self::getInstance();
    }

}