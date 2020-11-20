<?php

namespace AutoRestApi;

use Quantum\ApiException;
use Quantum\Middleware\Foundation\SystemMiddleware;

class ValidateRateLimit extends SystemMiddleware
{
    private $rate_limit;
    private $rate_limit_time;
    private $cache_key;

    public function __construct($rate_limit, $rate_limit_time, $cache_key)
    {
        $this->rate_limit = $rate_limit;
        $this->rate_limit_time = $rate_limit_time;
        $this->cache_key = $cache_key;
    }

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $ratelimiter = \Quantum\RateLimiterFactory::createFixedWindow($this->rate_limit, $this->rate_limit_time);

        $current_uri = hash("sha1", $this->cache_key);

        $loginThrottler = $ratelimiter->get($current_uri);

        if (!$loginThrottler->access()) {
            ApiException::custom('rate_limit', '429 Too Many Requests', 'Rate limit reached try again in '.round($loginThrottler->getRetryTimeout()/1000).' seconds');
        }

        set_header('X-RateLimit-Time', $this->rate_limit_time);
        set_header('X-RateLimit-Remaining', $this->rate_limit-$loginThrottler->count());

        $reset_time = $loginThrottler->getRetryTimeout();
        if ($reset_time > 0) {
            set_header('X-RateLimit-Reset', $reset_time);
        }
    }

}