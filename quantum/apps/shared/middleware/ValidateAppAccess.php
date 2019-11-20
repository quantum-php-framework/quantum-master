<?php


class ValidateAppAccess extends \Quantum\Middleware\Foundation\SystemMiddleware
{

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $hostedAppConfig = QM::config()->getHostedAppConfig();

        if ($hostedAppConfig->has('allowed_ips'))
        {
            $ips = $hostedAppConfig->getAllowedIps();

            if (!empty($ips) && $request->isFromIp($ips))
                return;

            $this->logException('ip_not_allowed');

            $this->getOutput()->displaySystemError('access_denied');
        }

        if ($hostedAppConfig->has('blocked_ips'))
        {
            $ips = $hostedAppConfig->getBlockedIps();

            if (!empty($ips) && $request->isFromIp($ips))
            {
                $this->logException('ip_blocked');
                $this->getOutput()->displaySystemError('access_denied');
            }

        }

        if ($hostedAppConfig->has('allowed_countries'))
        {
            $visitor_country_code = $request->getVisitorCountryCode();

            $countries = $hostedAppConfig->getAllowedCountries();

            if (!empty($countries)&& in_array($visitor_country_code, $countries))
                return;

            $this->logException('country_not_allowed', $visitor_country_code);
            $this->getOutput()->displaySystemError('country_access_denied');
        }

        if ($hostedAppConfig->has('blocked_countries'))
        {
            $visitor_country_code = $request->getVisitorCountryCode();

            $countries = $hostedAppConfig->getBlockedCountries();

            if (!empty($countries) && in_array($visitor_country_code, $countries))
            {
                $this->logException('blocked_country', $visitor_country_code);
                $this->getOutput()->displaySystemError('country_access_denied');
            }
        }

    }



}