<?php

/*
 * class FrontendController
 */
namespace AutoRestApi\Controllers;

use AutoRestApi\ApiVersion;
use AutoRestApi\ModelDescription;
use Quantum\ApiException;
use Quantum\ApiOutput;
use Quantum\ControllerFactory;
use Quantum\Output;
use Quantum\RequestParamValidator;
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
        $this->setHttpHeaderOverrideKeyIfNeeded();
    }

    private function setHttpHeaderOverrideKeyIfNeeded()
    {
        $http_method_header_override_key = $this->api_version->getHttpRequestMethodHeaderKeyOverride();

        if (!empty($http_method_header_override_key) && is_string($http_method_header_override_key)) {

            $http_method_header_override_key = qs($http_method_header_override_key)->toUpperCase()->replace('-', '_')->toStdString();

            if ($this->request->hasHeader($http_method_header_override_key)) {
                $_SERVER['REQUEST_METHOD'] = $this->request->getHeader($http_method_header_override_key);
            }
        }
    }

    private function addExtraData($data)
    {
        $extra_data =  $this->api_version->getExtraData();
        if (!empty($extra_data))
        {
            foreach ($extra_data as $key => $extra_datum)
            {
                $data[$key] = $extra_datum;
            }
        }

        return $data;
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

                $data['cache'] = 'off';

                $this->outputData($data);
            }
            else
            {
                $ttl = (int)$this->model_description->getCacheTimeToLive();

                $cache_key = qs($this->request->getUriWithQueryString())->sha1()->toStdString();

                $cache_hit = true;
                $data = from_cache($cache_key , function () use (&$cache_hit) {
                    $cache_hit = false;
                    $controller = ControllerFactory::create('AutoRestApi\Controllers\ListController');
                    return $controller->execute($this->model_description);
                }, $ttl);

                $data['cache'] = $cache_hit ? 'hit' : 'miss';

                $this->outputData($data);
            }

        }
        elseif ($this->request->isPost())
        {
            if (!$this->model_description->allowCreate()) {
                ApiException::invalidParameters();
            }
            $controller = ControllerFactory::create('AutoRestApi\Controllers\CreateController');

            $data = $controller->execute($this->model_description);

            $this->outputData($data);
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

        $data = $controller->execute($this->model_description);

        $this->outputData($data);

    }

    private function getCustomRouteData($route)
    {
        $real_controller = $route->get('real_controller');
        $real_method = $route->get('real_method');

        $request_methods = qs($route->get('http_request_methods', 'GET'))->explode('|');

        $is_request_allowed = false;

        $validator_rules = $route->get('validator_rules', []);

        foreach ($request_methods as $request_method)
        {
            if (!empty($validator_rules))
            {
                $request_method = qs($request_method)->toUpperCase()->toStdString();

                switch ($request_method)
                {
                    case 'GET':

                        $validator = new RequestParamValidator();
                        $validator->rules($validator_rules);

                        if (!$validator->validateGet()) {
                            ApiException::custom('validation_errors', '200', json_encode($validator->getErrors()));
                        }
                        break;

                    case 'POST':

                        $validator = new RequestParamValidator();
                        $validator->rules($validator_rules);

                        if (!$validator->validatePost()) {
                            ApiException::custom('validation_errors', '200', json_encode($validator->getErrors()));
                        }
                        break;

                    case 'PUT':

                        $validator = new RequestParamValidator();
                        $validator->rules($validator_rules);

                        if (!$validator->validatePut()) {
                            ApiException::custom('validation_errors', '200', json_encode($validator->getErrors()));
                        }
                        break;
                }
            }

            if (qs($this->request->getMethod())->equalsIgnoreCase($request_method)) {
                $is_request_allowed = true;
            }
        }

        if (!$is_request_allowed) {
            ApiException::invalidRequest();
        }

        $controller_name = qs($real_controller)->toTitleCase()->ensureRight("Controller")->toStdString();
        $controller = ControllerFactory::create($controller_name);

        $reflection = new \ReflectionMethod($controller, $real_method);
        if ($reflection->isProtected() || $reflection->isPrivate()) {
            ApiException::resourceNotFound();
        }

        $data = call_user_func_array([$controller, $real_method], [$this->model_description]);

        return $data;
    }

    public function custom_route()
    {
        $route = get_current_route();

        $ttl = $route->get('cache_ttl', 0);
        $cache_key = qs($this->request->getUriWithQueryString())->sha1()->toStdString();

        if ($ttl > 0 && $this->request->isGet())
        {
            $cache_hit = true;

            $data = from_cache($cache_key, function () use (&$route, &$cache_hit) {
                $cache_hit = false;
                return $this->getCustomRouteData($route);
            }, $ttl);

            $data['cache'] = $cache_hit ? 'hit' : 'miss';
        }
        else
        {
            $data = $this->getCustomRouteData($route);

            $data['cache'] = 'off';
        }

        $this->outputData($data);
    }

    public function index()
    {
        $controller = ControllerFactory::create('AutoRestApi\Controllers\IndexController');
        $controller->setApiRoutes($this->api_routes);
        $controller->setApiVersion($this->api_version);

        $controller->execute();
    }

    private function outputData($data)
    {
        $data = $this->addExtraData($data);

        if (is_vt($data))
            $data = $data->toStdArray();

        $pretty_print = $this->api_version->shouldPrettyPrintJson();

        ApiOutput::adaptableOutput($data, false, $pretty_print);
    }


}
