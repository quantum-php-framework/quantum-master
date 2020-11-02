<?php

namespace AutoRestApi;

class RequestDecoder
{
    private $version;

    public function __construct(VersionsManager $manager)
    {
        $this->request = qm_request();
        $this->version_manager = $manager;
    }

    public function getVersion()
    {
        if (!isset($this->version))
        {
            $uri = get_current_route_setting('uri');

            foreach ($this->version_manager->getVersions() as $version)
            {
                $generator = $version->getRouteGenerator();

                $route = $generator->findRouteByUri($uri);

                if ($route) {
                    $this->version = $version;
                }
            }
        }

        return $this->version;
    }

    public function getModelDescription()
    {
        $route = get_current_route();

        if ($route) {
            return $route['model_description'];
        }

        return null;
    }

}