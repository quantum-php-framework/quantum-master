<?php

use Quantum\Request;

class CorsHandler extends \Quantum\Middleware\Foundation\SystemMiddleware
{

    public function handle(Request $request, \Closure $closure)
    {
        if (!$this->isCorsRequest($request))
        {
            return;
        }

        $route = QM::config()->getCurrentRoute();

        if ($route === false)
            $this->getOutput()->display404();

        if (!$route->has('cors_enabled'))
            return;

        $this->route = $route;

        if (!$this->isRequestAllowed($request))
        {
            $this->handleDeniedRequest($request);
        }

        if ($this->isPreflightRequest($request))
        {
            $this->handlePreflightRequest($request);
        }

        $this->addCorsHeaders();

    }

    private function handlePreflightRequest(Request $request)
    {
        $output = $this->getOutput();

        $this->addPreflightHeaders($output);

        $output->response(null, 204);
    }

    private function handleDeniedRequest(Request $request)
    {
        Quantum\ApiException::custom('Denied', $this->getDeniedResponseStatusCode(), $this->getDeniedResponseMessage());
    }


    private function isRequestAllowed(Request $request)
    {
        if (!qs($this->getAllowedMethods())->containsWholeWordIgnoreCase($request->getMethod()))
            return false;

        $origins = qs($this->getAllowedOrigins());

        if ($origins->contains("*"))
            return true;

        return $origins->containsWholeWordIgnoreCase($request->getHeaderIgnoreCase('Origin'));
    }

    private function addCorsHeaders()
    {
        $response = $this->getOutput();

        if ($this->allowCredentials())
        {
            $response->setHeaderParam('Access-Control-Allow-Credentials', 'true');
        }

        $response->setHeaderParam('Access-Control-Allow-Origin', $this->getAllowedOrigins());
        $response->setHeaderParam('Access-Control-Expose-Headers', $this->getExposeHeaders());

        return $response;
    }


    private function addPreflightHeaders($response)
    {
        if ($this->allowCredentials())
        {
            $response->setHeaderParam('Access-Control-Allow-Credentials', 'true');
        }

        $response->setHeaderParam('Access-Control-Allow-Methods', $this->getAllowedMethods());
        $response->setHeaderParam('Access-Control-Allow-Headers', $this->getAllowedHeaders());
        $response->setHeaderParam('Access-Control-Allow-Origin', $this->getAllowedOrigins());
        $response->setHeaderParam('Access-Control-Max-Age', $this->getMaxAge());
        $response->setHeaderParam('Content-Length', 0);
        $response->removeHeader("Content-type");


        return $response;
    }


    private function isCorsRequest(Request $request)
    {
        $origin = $request->getHeaderIgnoreCase('Origin');

        if (!$origin)
        {
            return false;
        }

        $origin = qs($origin);
        $current_url = qs($request->getSchemeAndHttpHost());

        return !$origin->equalsIgnoreCase($current_url);
    }

    private function isPreflightRequest(Request $request)
    {
        return $request->isOptions() && $request->hasHeaderIgnoreCase('Access-Control-Request-Method');
    }

    private function allowCredentials()
    {
        return $this->route->get('cors_allow_credentials', false) == true;
    }

    private function getAllowedMethods()
    {
        return $this->route->get('cors_allowed_methods', 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS');
    }

    private function getAllowedHeaders()
    {
        return $this->route->get('cors_allowed_headers', 'Content-Type, X-Auth-Token, Origin, Authorization, X-Requested-With, Accept');
    }

    private function getAllowedOrigins()
    {
        return $this->route->get('cors_allowed_origins', '*');
    }

    private function getExposeHeaders()
    {
        return $this->route->get('cors_expose_headers', 'Cache-Control, Content-Language, Content-Type, Expires, Last-Modified, Pragma');
    }

    private function getDeniedResponseMessage()
    {
        return $this->route->get('cors_denied_response_message', 'Denied CORS Request');
    }

    private function getDeniedResponseStatusCode()
    {
        return $this->route->get('cors_denied_response_status_code', '403');
    }

    private function getMaxAge()
    {
        return $this->route->get('cors_max_age', 86400);
    }



}