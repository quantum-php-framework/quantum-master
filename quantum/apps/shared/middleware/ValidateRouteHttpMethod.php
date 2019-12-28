<?php

class ValidateRouteHttpMethod extends \Quantum\Middleware\Foundation\SystemMiddleware
{

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $route = \QM::config()->getCurrentRoute();

        if ($route === false)
            return;

        if (!$route->has('http_request_methods'))
            return;

        $methods = qs($route->get('http_request_methods'))->toLowerCase()->explode('|');

        $access_granted = false;

        foreach ($methods as $method)
        {
            switch ($method)
            {
                case 'get':
                    if ($request->isGet())
                        $access_granted = true;
                    break;

                case 'post':
                    if ($request->isPost())
                        $access_granted = true;
                    break;

                case 'put':
                    if ($request->isPut())
                        $access_granted = true;
                    break;

                case 'delete':
                    if ($request->isDelete())
                        $access_granted = true;
                    break;

                case 'patch':
                    if ($request->isPatch())
                        $access_granted = true;
                    break;

                case 'head':
                    if ($request->isHead())
                        $access_granted = true;
                    break;

                case 'options':
                    if ($request->isOptions())
                        $access_granted = true;
                    break;

                default:
                    \Quantum\ApiException::custom("invalid_request_method", '500', 'Invalid request method:'.$method);
                    break;
            }

        }

        if (!$access_granted)
            $this->getOutput()->displayAppError('500');


    }

}