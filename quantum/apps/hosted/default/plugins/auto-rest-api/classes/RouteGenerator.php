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
        $routes [] = $this->prepareRoute([
            'uri' => '/'.$this->api_prefix.'/index',
            'controller' => 'AutoRestApi\Controllers\Frontend',
            'method' => 'index',
            'model_description' => 'index'
        ]);

        $models = $this->models_manager->getModels();

        foreach ($models as $model)
        {
            $description = new ModelDescription($model);

            if ($description->allowList())
            {
                $routes [] = $this->prepareRoute([
                    'uri' => '/'.$this->api_prefix.'/'.$model['plural_form'],
                    'controller' => 'AutoRestApi\Controllers\Frontend',
                    'method' => 'list',
                    'model_description' => $description
                ]);
            }

            if ($description->allowView())
            {
                $routes [] = $this->prepareRoute([
                    'uri' => '/' . $this->api_prefix . '/' . $model['plural_form'] . '/{id}',
                    'controller' => 'AutoRestApi\Controllers\Frontend',
                    'method' => 'view',
                    'model_description' => $description
                ]);
            }

            $extra_routes = $description->getExtraRoutes();
            foreach ($extra_routes as $extra_route)
            {
                $extra_route['uri'] = '/' . $this->api_prefix. '/' . $model['plural_form'] .$extra_route['uri'];

                $extra_route['real_controller'] = $extra_route['controller'];
                $extra_route['real_method'] = $extra_route['method'];

                $extra_route['controller'] = 'AutoRestApi\Controllers\Frontend';
                $extra_route['method'] =  'custom_route';


                $extra_route['model_description'] = $description;
                $extra_route = $this->prepareRoute($extra_route);

                $routes [] = $extra_route;
            }
        }

        return apply_filter('auto_rest_api_filter_generated_routes', $routes);
    }

    private function prepareRoute($route)
    {
        $route['templates']  = 'public_access|csrf_disabled';
        $route['page_cache'] = 0;

        return $route;
    }


    public function findRouteByUri($uri)
    {
        foreach ($this->routes as $route)
        {
            if ($route['uri'] == $uri)
            {
                return $route;
            }
        }

        return null;
    }



}