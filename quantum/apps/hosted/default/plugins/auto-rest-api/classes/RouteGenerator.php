<?php

namespace AutoRestApi;

class RouteGenerator
{
    public function __construct(ModelsManager $models_manager, $api_prefix)
    {
        $this->models_manager = $models_manager;
        $this->api_prefix = $api_prefix;
    }

    public function getRoutes()
    {
        if (!isset($this->routes)) {
            $this->routes = $this->genRoutes();
        }

        return $this->routes;
    }

    public function genRoutes()
    {
        $models = $this->models_manager->getModels();

        $routes [] = $this->prepareRoute([
            'uri' => '/'.$this->api_prefix.'/index',
            'controller' => 'AutoRestApi\Controllers\Frontend',
            'method' => 'index',
        ]);

        foreach ($models as $model)
        {
            $description = new ModelDescription($model);

            if ($description->allowList())
            {
                $routes [] = $this->prepareRoute([
                    'uri' => '/'.$this->api_prefix.'/'.$model['plural_form'],
                    'controller' => 'AutoRestApi\Controllers\Frontend',
                    'method' => 'list'
                ]);
            }

            if ($description->allowView())
            {
                $routes [] = $this->prepareRoute([
                    'uri' => '/' . $this->api_prefix . '/' . $model['plural_form'] . '/{id}',
                    'controller' => 'AutoRestApi\Controllers\Frontend',
                    'method' => 'view'
                ]);
            }
        }

        return $routes;
    }

    private function prepareRoute($route)
    {
        $route['templates']  = 'public_access|csrf_disabled';
        $route['page_cache'] = 0;

        return $route;
    }



}