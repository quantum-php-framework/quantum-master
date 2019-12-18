<?php

/**
 * A Quantum Hosted App
*/

class App extends Quantum\HostedApp
{
    function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        //$this->runMiddlewares([ValidateRouteHttpMethod::class]);
        QM::observe(\Quantum\Cache\ServiceProvider::BackendChangeEvent, function ($event)
        {
            qs($event->getData())->render();
        });
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