<?php

namespace AutoRestApi;

class ValidateAutoRestApiAccess extends \Quantum\Middleware\Foundation\SystemMiddleware
{
    public function __construct(ApiVersion $version)
    {
        $this->version = $version;
    }

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $auths = $this->version->getAuthorizations();

       foreach ($auths as $auth)
       {
           switch ($auth)
           {
               case 'appkey':
                   $middleware = new ValidateAppKeyAuth();
                   break;
               case 'basic':
                   $middleware = new ValidateBasicUserPwdAuth();
                   break;
               case 'userkey':
                   $middleware = new ValidateUserKeyAuth();
                   break;
               case 'custom':
                   $middleware_class = $this->version->getAuthorizationMiddleware();

                   $middleware = new $middleware_class($this->version);
                   break;
           }

           $middleware->handle($request, $closure);
       }
    }

}