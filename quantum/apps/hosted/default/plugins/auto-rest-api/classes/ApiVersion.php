<?php

namespace AutoRestApi;

class ApiVersion
{
    /**
     * @var RouteGenerator
     */
    private $route_generator;

    public function __construct($config, $models_file)
    {
        $this->config = $config;

        $api_prefix = 'api/v'.$this->getVersion();

        $this->route_generator = new RouteGenerator(new ModelsManager($models_file), $api_prefix);

    }

    public function getVersion()
    {
        return $this->config['version'];
    }

    public function getAuthorizationMiddleware()
    {
        return $this->config['autorization_middleware'];
    }

    public function getAuthorizations()
    {
        return qs($this->config['authorizations'])->explode(',');
    }

    public function getModelsFileName()
    {
        return qs($this->config['models_file'])->ensureRight('.php')->toStdString();
    }


    public function getRouteGenerator()
    {
        return $this->route_generator;
    }

    public function getModelsManager()
    {
        return $this->route_generator->models_manager;
    }
}