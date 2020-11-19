<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ModelDescription;
use Quantum\ApiException;
use Quantum\Controller;

class ViewController extends Controller
{

    /**
     * Create a controller, no dependency injection has happened.
     */
    function __construct()
    {

    }


    public function execute(ModelDescription $modelDescription)
    {
        $id = $this->request->getId();

        $modelName = $modelDescription->getClassName();

        $id_attribute = $modelDescription->getIdAttributeKey();
        $model = $modelName::find(array('conditions' => array("$id_attribute = ?", $id)));

        if (empty($model)) {
            ApiException::resourceNotFound();
        }

        return self::genVisibleData($model, $modelDescription);
    }

    public static function genVisibleData($model, ModelDescription $modelDescription)
    {
        $visible_attributes = $modelDescription->getVisibleAttributes();

        $requested_attributes = get_request_param($modelDescription->getFilteredAttributesParamKey(), null);
        if ($requested_attributes) {
            $requested_attributes = qs($requested_attributes)->explode(',');
        }

        $datum = new_vt();

        foreach ($visible_attributes as $attribute_name => $value)
        {
            if ( !empty($requested_attributes) && is_array($requested_attributes) && !in_array($attribute_name, $requested_attributes) ) {
                continue;
            }

            if (qs($value)->contains('()')) {
                $value = call_user_func([$model, qs($value)->removeCharacters('()')->toStdString()]);
            }
            else {
                $value = $model->$value;
            }

            $datum->set($attribute_name, $value);
        }

        $extra_data = $modelDescription->getExtraData();
        if (!empty($extra_data))
        {
            foreach ($extra_data as $key => $extra_datum)
            {
                $datum->set($key, $extra_datum);
            }
        }

        return $datum->toStdArray();
    }




}
