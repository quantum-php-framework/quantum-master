<?php

namespace Qontent\Entities;


class PostRouteHandler
{
    public function __construct()
    {

    }

    public function genRoute()
    {
        if (!$this->postExists()) {
            return false;
        }

        $route = $this->genBaseRoute();

        if (1 == 2) {
            $route->set('page_cache', 1);
        }

        return $route;
    }


    private function genBaseRoute()
    {
        $route = new_vt(array(
            'uri' => qm_request()->getUri(),
            'controller' => 'Qontent\Frontend\PostsController',
            'method' => 'post',
            'templates' => 'public_access|csrf_disabled'
        ));

        return $route;
    }

    private function postExists()
    {
        return true;
    }
}