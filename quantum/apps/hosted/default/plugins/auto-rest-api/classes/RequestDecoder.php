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
            $uri = $this->request->getUriWithoutQueryString();

            $api_version = qs($uri)->fromFirstOccurrenceOf('api/v')->upToFirstOccurrenceOf('/');

            $this->version = $this->version_manager->getVersion($api_version);
        }

        return $this->version;
    }

    public function getModelDescription()
    {
        $version = $this->getVersion();

        $api_prefix = $version->getRouteGenerator()->api_prefix;

        $uri = $this->request->getUriWithoutQueryString();

        $model_path = qs($uri)->fromFirstOccurrenceOf($api_prefix.'/')->upToFirstOccurrenceOf('/')->toStdString();

        $models = $version->getModelsManager()->getModels();

        foreach ($models as $model)
        {
            if ($model['plural_form'] == $model_path) {
                return new ModelDescription($model);
            }
        }

       return null;
    }

    public function isIndex()
    {
        $version = $this->getVersion();

        if (!$version) {
            return false;
        }

        $api_prefix = $version->getRouteGenerator()->api_prefix;

        $uri = $this->request->getUriWithoutQueryString();

        $model_path = qs($uri)->fromFirstOccurrenceOf($api_prefix.'/')->upToFirstOccurrenceOf('/');

        return $model_path->equalsIgnoreCase('index');
    }
}