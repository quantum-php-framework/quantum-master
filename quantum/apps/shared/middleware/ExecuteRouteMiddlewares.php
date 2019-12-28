<?php

namespace Shared\Middlewares;

use Quantum\Middleware\Request\RunHandler;

class ExecuteRouteMiddlewares extends \Quantum\Middleware\Foundation\SystemMiddleware
{

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $route = \QM::config()->getCurrentRoute();

        if ($route === false)
            return;

        $middlewares = $route->get('middlewares', []);

        if (empty($middlewares))
            return;

        $handler = new RunHandler();
        $handler->registerProviders($middlewares);
        $handler->runRequestProviders($request, $closure);

    }

}