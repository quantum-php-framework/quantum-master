<?php

namespace AutoRestApi;

class ModelDescription
{
    public function __construct($desc)
    {
        $this->description = $desc;
    }

    public function getClassName()
    {
        return $this->description['class_name'];
    }

    public function getPluralForm()
    {
        return $this->description['plural_form'];
    }

    public function getVisibleAttributes()
    {
        return $this->description['visible_attributes'];
    }

    public function getEditableAttributes()
    {
        return $this->description['editable_attributes'];
    }

    public function getSearchableAttributes()
    {
        return $this->description['searchable_attributes'];
    }

    public function getFeatures()
    {
        return qs($this->description['features'])->explode(',');
    }

    public function allowList()
    {
        return $this->allowFeature('list');
    }

    public function allowCreate()
    {
        return $this->allowFeature('create');
    }

    public function allowView()
    {
        return $this->allowFeature('view');
    }

    public function allowUpdate()
    {
        return $this->allowFeature('update');
    }

    public function allowDelete()
    {
        return $this->allowFeature('delete');
    }

    public function allowFeature($name)
    {
        $features = $this->getFeatures();

        return in_array($name, $features);
    }

}
