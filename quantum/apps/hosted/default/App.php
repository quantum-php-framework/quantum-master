<?php

/**
 * A Quantum Hosted App
*/

class App extends Quantum\HostedApp
{
    function __construct()
    {
        parent::__construct();
        //$this->rollbackMigrations();
    }

    public function init()
    {
        //$this->runMiddlewares([\Shared\Middlewares\ExecuteRouteMiddlewares::class]);
        //$this->runMiddlewares([SetRouteCacheHeader::class, CorsHandler::class]);
        $this->runMiddlewares([ValidateAppAccess::class,
            ValidateAppRoutes::class,
            ValidateAppRoutesRateLimit::class,
            ValidateRouteAccessLevel::class,
            PageCacheMiddleware::class]);

    }

    public function pre_controller_construct()
    {

    }

    public function setActiveController($controller)
    {
         parent::setActiveController($controller);
    }

    public function pre_controller_dispatch()
    {

    }

    public function post_controller_dispatch()
    {

    }

    public function pre_render()
    {

    }

    public function post_render()
    {

    }

    public function shutdown()
    {

    }
}