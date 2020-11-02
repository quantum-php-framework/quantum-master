<?php

namespace AutoRestApi;

class ModelDescription
{
    public function __construct($desc)
    {
        $this->desc = new_vt($desc);
    }

    public function getClassName()
    {
        return $this->desc->get('class_name');
    }

    public function getPluralForm()
    {
        return $this->desc->get('plural_form');
    }

    public function getSingularForm()
    {
        return $this->desc->get('singular_form');
    }

    public function getVisibleAttributes()
    {
        return $this->desc->get('visible_attributes');
    }

    public function getEditableAttributes()
    {
        return $this->desc->get('editable_attributes');
    }

    public function getSearchableAttributes()
    {
        return $this->desc->get('searchable_attributes');
    }

    public function getFeatures()
    {
        return qs($this->desc->get('features'))->stripWhitespace()->explode(',');
    }

    public function getOperators()
    {
        return qs($this->desc->get('operators', 'OR,AND'))->stripWhitespace()->explode(',');
    }

    public function getUniqueAttributes()
    {
        return qs($this->desc->get('unique_attributes', ''))->stripWhitespace()->explode(',');
    }

    public function getPrimaryIndexAttributeKey()
    {
        return $this->desc->get('primary_index_attribute_key', 'id');
    }

    public function isOperatorAllowed($operator)
    {
        return in_array($operator, $this->getOperators());
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

    public function allowSearch()
    {
        return $this->allowFeature('search');
    }

    public function allowFeature($name)
    {
        $features = $this->getFeatures();

        return in_array($name, $features);
    }

}
