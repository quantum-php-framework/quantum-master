<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ModelDescription;
use Quantum\ApiException;
use Quantum\Controller;
use Quantum\ControllerFactory;

class UpdateController extends Controller
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

        if (qs($id)->isUuid()) {
            $model = $modelName::find(array('conditions' => array("uuid = ?", $id)));
        }
        elseif (qs($id)->isNumber()) {
            $model = $modelName::find(array('conditions' => array("id = ?", $id)));
        }

        if (empty($model)) {
            ApiException::resourceNotFound();
        }

        $primary_model_key = $modelDescription->getPrimaryIndexAttributeKey();

        $unique_attributes = $modelDescription->getUniqueAttributes();
        $editable_attributes = $modelDescription->getEditableAttributes();

        foreach ($editable_attributes as $attribute_name => $request_param_key)
        {
            if ($this->request->isMissingParam($request_param_key)) {
                continue;
            }

            if (in_array($attribute_name, $unique_attributes))
            {
                $previous_object = $modelName::find(array('conditions' => ["$attribute_name = ?", $this->request->getParam($request_param_key)]));

                if (!empty($previous_object) && $previous_object->$primary_model_key != $model->$primary_model_key) {
                    ApiException::custom('duplicate_entry', '400 Invalid', 'Duplicate object attribute found for '.$attribute_name);
                }
            }

            $model->$attribute_name = $this->request->getParam($request_param_key);
        }

        dispatch_event('auto_rest_api_before_model_update', $model);

        $model->save();

        dispatch_event('auto_rest_api_after_model_update', $model);

        $controller = ControllerFactory::create('AutoRestApi\Controllers\ViewController');
        $controller->displayModel($model, $modelDescription);
    }



}