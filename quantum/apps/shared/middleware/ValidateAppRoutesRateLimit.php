<?php


class ValidateAppRoutesRateLimit extends \Quantum\Middleware\Foundation\SystemMiddleware
{

    /* O
     *
     * https://github.com/sunspikes/php-ratelimiter
     */
    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $current_uri = $request->getUri();

        $route = \QM::config()->getCurrentRoute();

        if (empty($route))
            \Quantum\ApiException::resourceNotFound("0XVRL0x00");

        if ($route->has('rate_limit') && $route->has('rate_limit_time'))
        {
            $app_config = \QM::config()->getHostedAppConfig();

            $ratelimiter = \Quantum\RateLimiterFactory::create($route->get('rate_limit'), $route->get('rate_limit_time'));

            $current_uri = "APP_".strtoupper($app_config->getUri())."_ROUTE_".str_replace("/","@", strtoupper($current_uri))."@";
            $current_uri = hash("crc32b", $current_uri);
            $loginThrottler = $ratelimiter->get($current_uri);

            if (!$loginThrottler->access())
            {
                $this->logException('route_rate_limit_reached', $request->getUri());
                \Quantum\ApiException::rateLimitReached();
            }

        }

        if ($route->has('session_rate_limit') && $route->has('session_rate_limit_time'))
        {
            $app_config = \QM::config()->getHostedAppConfig();

            $ratelimiter = \Quantum\RateLimiterFactory::create($route->get('session_rate_limit'), $route->get('session_rate_limit_time'));

            $current_uri = "APP_".strtoupper($app_config->getUri())."_ROUTE_".str_replace("/","@", strtoupper($current_uri))."@_".QM::session()->getId();
            $current_uri = hash("crc32b", $current_uri);
            $loginThrottler = $ratelimiter->get($current_uri);

            if (!$loginThrottler->access())
            {
                $this->logException('session_rate_limit_reached', $request->getUri());
                \Quantum\ApiException::sessionRateLimitReached();
            }

        }



    }

}