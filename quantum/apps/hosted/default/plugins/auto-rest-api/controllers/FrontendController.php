<?php

/*
 * class FrontendController
 */
namespace AutoRestApi\Controllers;

use AutoRestApi\ApiVersion;
use AutoRestApi\ModelDescription;
use Quantum\ApiException;
use Quantum\ControllerFactory;
use function foo\func;

class FrontendController extends \Quantum\Controller
{
    /**
     * @var ModelDescription
     */
    private $model_description;
    private $api_routes;

    /**
     * @var ApiVersion
     */
    private $api_version;

    /**
     * Create a controller, no dependency injection has happened.
     */
    function __construct()
    {

    }

    /**
     * Called after dependency injection, all environment variables are ready.
     */
    protected function __post_construct()
    {
        $this->setAutoRender(false);
    }

    /**
     * Called before calling the main controller action, all environment variables are ready.
     */
    protected function __pre_dispatch()
    {

    }

    /**
     * Called after calling the main controller action, all vars set by controller are ready.
     */
    protected function __post_dispatch()
    {

    }

    /**
     * Called after calling the main controller action, before calling Quantum\Output::render
     */
    protected function __pre_render()
    {

    }

    /**
     * Called after calling Quantum\Output::render
     */
    protected function __post_render()
    {

    }

    public function setModelDescription(ModelDescription $model_description)
    {
        $this->model_description = apply_filter('auto_rest_api_filter_model_description', $model_description);
    }

    public function setApiRoutes($routes)
    {
        $this->api_routes = $routes;
    }

    public function setApiVersion(ApiVersion $version)
    {
        $this->api_version = $version;
    }


    public function list()
    {
        if(!$this->model_description->allowList()) {
            ApiException::resourceNotFound();
        }

        if ($this->request->isGet())
        {
            if (!$this->model_description->isCacheEnabled())
            {
                $controller = ControllerFactory::create('AutoRestApi\Controllers\ListController');

                $data = $controller->execute($this->model_description);

                $this->output->adaptable($data);
            }
            else
            {
                $ttl = (int)$this->model_description->getCacheTimeToLive();

                $cache_key = new_vt($this->request->getGetParams())->getHash();

                $data = from_cache($cache_key , function () {
                    $controller = ControllerFactory::create('AutoRestApi\Controllers\ListController');
                    return $controller->execute($this->model_description);
                }, $ttl);

                $this->output->adaptable($data);
            }

        }
        elseif ($this->request->isPost())
        {
            if (!$this->model_description->allowCreate()) {
                ApiException::invalidParameters();
            }
            $controller = ControllerFactory::create('AutoRestApi\Controllers\CreateController');
            $controller->execute($this->model_description);
        }
        else
        {
            ApiException::invalidRequest();
        }


    }


    public function view()
    {
        if(!$this->model_description->allowView()) {
            ApiException::resourceNotFound();
        }

        if ($this->request->isGet())
        {
            $controller = ControllerFactory::create('AutoRestApi\Controllers\ViewController');
        }
        elseif ($this->request->isPost() || $this->request->isPut())
        {
            if (!$this->model_description->allowUpdate()) {
                ApiException::invalidParameters();
            }

            $controller = ControllerFactory::create('AutoRestApi\Controllers\UpdateController');
        }
        elseif ($this->request->isDelete())
        {
            if (!$this->model_description->allowDelete()) {
                ApiException::invalidParameters();
            }

            $controller = ControllerFactory::create('AutoRestApi\Controllers\DeleteController');
        }
        else
        {
            ApiException::invalidRequest();
        }

        $controller->execute($this->model_description);
    }


    public function index()
    {
        $controller = ControllerFactory::create('AutoRestApi\Controllers\IndexController');
        $controller->setApiRoutes($this->api_routes);
        $controller->setApiVersion($this->api_version);

        $controller->execute();
    }


}
