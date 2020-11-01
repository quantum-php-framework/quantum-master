<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ApiVersion;
use Quantum\Controller;


class IndexController extends Controller
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

        $paths = apply_filter('auto_rest_api_filter_apis_list', $paths->toStdArray());

        $response->set('paths', $paths);

        $this->output->adaptable($response);
    }



}
