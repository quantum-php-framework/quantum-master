<?php


class SetRouteCacheHeader extends \Quantum\Middleware\Foundation\SystemMiddleware
{

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $route = QM::config()->getCurrentRoute();

        if ($route === false)
            $this->getOutput()->display404();

        if (!$route->has('http_cache_maxage'))
            return;

        $maxage = $route->get('http_cache_maxage');

        $header = 'Cache-Control: public, must-revalidate, max-age='.$maxage;

        if ($route->has('http_shared_cache_maxage'))
        {
            $header .= ', s-maxage= '.$route->get('http_shared_cache_maxage');
        }

        $this->getOutput()->setHeader($header);

    }
}