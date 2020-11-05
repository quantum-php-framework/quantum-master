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

    public function getCreatableAttributes()
    {
        $attributes = $this->desc->get('creatable_attributes', []);

        if (!empty($attributes)) {
            return $attributes;
        }

        return $this->getEditableAttributes();
    }

    public function getFeatures()
    {
        return qs($this->desc->get('features'))->stripWhitespace()->explode(',');
    }

    public function getAllowedOperators()
    {
        return qs($this->desc->get('allowed_operators', 'OR,AND'))->stripWhitespace()->explode(',');
    }

    public function getAllowedOrders()
    {
        return qs($this->desc->get('allowed_orders', 'DESC,ASC'))->stripWhitespace()->explode(',');
    }

    public function getUniqueAttributes()
    {
        return qs($this->desc->get('unique_attributes', ''))->stripWhitespace()->explode(',');
    }

    public function getIdAttributeKey()
    {
        return $this->desc->get('id_attribute', 'id');
    }

    public function getCreateValidatorRules()
    {
        return $this->desc->get('create_validator_rules', []);
    }

    public function getUpdateValidatorRules()
    {
        return $this->desc->get('update_validator_rules', []);
    }

    public function getOrderAttributeKey()
    {
        return $this->desc->get('order_attribute', 'id');
    }

    public function getDefaultOrder()
    {
        return $this->desc->get('default_order', 'DESC');
    }

    public function getDefaultLimit()
    {
        return $this->desc->get('default_limit', 25);
    }

    public function getMaxLimit()
    {
        return $this->desc->get('max_limit', 1000);
    }

    public function getCacheTimeToLive()
    {
        return $this->desc->get('cache_ttl', false);
    }

    public function isCacheEnabled()
    {
        return $this->getCacheTimeToLive() != false;
    }

    public function isOperatorAllowed($operator)
    {
        return in_array($operator, $this->getAllowedOperators());
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
