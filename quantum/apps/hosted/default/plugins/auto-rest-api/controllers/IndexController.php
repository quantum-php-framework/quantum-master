<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ApiVersion;


class IndexController extends \Quantum\Controller
{


    /**
     * Create a controller, no dependency injection has happened.
     */
    function __construct()
    {

    }

    public function setApiRoutes($routes)
    {
        $this->api_routes = $routes;
    }

    public function setApiVersion(ApiVersion $version)
    {
        $this->version = $version;
    }


    public function execute()
    {
        $response = new_vt();
        $response->set('version', $this->version->getVersion());

        $paths = new_vt();

        foreach ($this->api_routes as $route)
        {
            $datum = new_vt();

            $datum['url'] = \QM::buildURL($route['uri'], false);

            $paths->add($datum->toStdArray());
        }

        $response->set('paths', $paths->toStdArray());

        $this->output->adaptable($response);
    }



}
