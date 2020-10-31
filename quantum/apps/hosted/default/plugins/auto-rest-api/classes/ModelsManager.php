<?php

namespace AutoRestApi;

class ModelsManager
{
    public function __construct($models_file)
    {
        $this->models = include $models_file->getRealPath();
    }

    public function getModels()
    {
        return $this->models;
    }
}