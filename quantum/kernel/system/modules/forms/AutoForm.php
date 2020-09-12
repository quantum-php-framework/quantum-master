<?php


namespace Quantum;

class SpeciallyHandledParameter
{
    public function __construct($key, $callable)
    {
        $this->key = $key;
        $this->callable = $callable;
    }

    public function callback()
    {
        return call_user_func($this->callable);
    }
}


class AutoForm
{
    public function __construct(ActiverecordModel $model, \AdminFormElementsFactory $factory, $excluded_params = array(), $special_parameters = array())
    {
        $this->model = $model;
        $this->factory = $factory;
        $this->excluded_params = $excluded_params;
        $this->special_params = array();

        $this->setSpecialParams($special_parameters);
    }

    public function setSpecialParams($special_parameters)
    {
        foreach ($special_parameters as $key => $callable)
        {
            $this->setParamHandler($key, $callable);
        }
    }

    public function setExcludedParams($params)
    {
        $this->excluded_params = $params;
    }

    public function setParamHandler($key, $callable)
    {
        $special_param = new SpeciallyHandledParameter($key, $callable);
        $this->special_params[$key] = $special_param;
    }

    public function createEmpty()
    {
        $form = new Form($this->factory);
        return $form;
    }

    public function create()
    {
        $form = $this->createEmpty();

        $attributes = $this->model->attributes();

        foreach ($attributes as $key => $attribute)
        {

            if (!in_array($key, $this->excluded_params))
            {
                if (!array_has($this->special_params, $key))
                {
                    $form->text(qs($key)->humanize()->toStdString(), $key, $attribute);
                }
                else
                {
                    $specialParam = $this->special_params[$key];
                    $form->custom(qs($key)->humanize()->toStdString(), $key, $specialParam->callback());
                }

            }
        }

        return $form;
    }

    public function createWithAllowedParams($allowed_params)
    {
        $attributes = array_keys($this->model->attributes());
        $excluded_params = array_diff($attributes, $allowed_params);

        $this->setExcludedParams($excluded_params);

        return $this->create();

    }

}