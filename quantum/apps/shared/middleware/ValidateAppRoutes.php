<?php


class ValidateAppRoutes extends \Quantum\Middleware\Foundation\SystemMiddleware
{

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $route = QM::config()->getCurrentRoute();

        if ($route === false)
            $this->getOutput()->display404();

        if (qs($route->get('uri'))->contains('{id}'))
        {
            if (!$request->hasId())
            {
                $this->logException('missing_id');
                $this->getOutput()->display404();
            }

        }

        if ($route->has('allowed_ips'))
        {
            $ips = $route->getAllowedIps();

            if (!empty($ips) && $request->isFromIp($ips))
                return;

            $this->logException('ip_not_allowed');
            $this->getOutput()->displaySystemError('access_denied');
        }

        if ($route->has('blocked_ips'))
        {
            $ips = $route->getBlockedIps();

            if (!empty($ips) && $request->isFromIp($ips))
            {
                $this->logException('blocked_ip');
                $this->getOutput()->displaySystemError('access_denied');
            }

        }

        if ($route->has('allowed_countries'))
        {
            $visitor_country_code = $request->getVisitorCountryCode();

            $countries = $route->getAllowedCountries();

            if (!empty($countries) && in_array($visitor_country_code, $countries))
                return;

            $this->logException('country_not_allowed');
            $this->getOutput()->displaySystemError('country_access_denied');
        }

        if ($route->has('blocked_countries'))
        {
            $visitor_country_code = $request->getVisitorCountryCode();

            $countries = $route->getBlockedCountries();

            if (!empty($countries) && in_array($visitor_country_code, $countries))
            {
                $this->logException('blocked_country');
                $this->getOutput()->displaySystemError('country_access_denied');
            }

        }

    }



}