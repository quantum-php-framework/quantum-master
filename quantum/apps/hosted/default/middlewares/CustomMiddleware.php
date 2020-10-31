<?php

use AutoRestApi\ApiVersion;
use Quantum\ApiException;

class CustomMiddleware extends \Quantum\Middleware\Foundation\SystemMiddleware
{
    public function __construct(ApiVersion $version)
    {
        $this->version = $version;
    }

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        //ApiException::accessDenied();
    }

}