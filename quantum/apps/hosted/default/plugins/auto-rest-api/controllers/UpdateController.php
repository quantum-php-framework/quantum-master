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

        $editable_attributes = $modelDescription->getEditableAttributes();

        foreach ($editable_attributes as $attribute_name => $request_param_key)
        {
            $model->$attribute_name = $this->request->getParam($request_param_key);
        }

        dispatch_event('auto_rest_api_before_model_update', $model);

        $model->save();

        dispatch_event('auto_rest_api_after_model_update', $model);

        $controller = ControllerFactory::create('AutoRestApi\Controllers\ViewController');
        $controller->displayModel($model, $modelDescription);
    }



}