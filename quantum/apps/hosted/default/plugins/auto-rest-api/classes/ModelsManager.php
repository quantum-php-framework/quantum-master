<?php

namespace AutoRestApi;

class ModelsManager
{
    public function __construct($models)
    {
        $this->models = $models;
    }

    public function getModels()
    {
        return $this->models;
    }
}