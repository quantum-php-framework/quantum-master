<?php

/*
 * class IndexController
 */
namespace AutoRestApi\Controllers;

use AutoRestApi\ApiVersion;
use AutoRestApi\ModelDescription;
use AutoRestApi\RequestDecoder;
use Quantum\ApiException;
use Quantum\ControllerFactory;

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
        $this->model_description = $model_description;
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
            $controller = ControllerFactory::create('AutoRestApi\Controllers\ListController');
        }
        elseif ($this->request->isPost())
        {
            if (!$this->model_description->allowCreate()) {
                ApiException::invalidParameters();
            }
            $controller = ControllerFactory::create('AutoRestApi\Controllers\CreateController');
        }
        else
        {
            ApiException::invalidParameters();
        }

        $controller->execute($this->model_description);
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
