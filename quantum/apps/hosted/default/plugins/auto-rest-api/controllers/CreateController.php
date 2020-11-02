<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ModelDescription;
use Quantum\ApiException;
use Quantum\Controller;
use Quantum\ControllerFactory;

class CreateController extends Controller
{


    /**
     * Create a controller, no dependency injection has happened.
     */
    function __construct()
    {

    }



    public function execute(ModelDescription $modelDescription)
    {
        $className = $modelDescription->getClassName();

        $object = new $className();

        $unique_attributes = $modelDescription->getUniqueAttributes();
        $editable_attributes = $modelDescription->getEditableAttributes();

        foreach ($editable_attributes as $attribute_name => $request_param_key)
        {
            if ($this->request->isMissingParam($request_param_key)) {
                continue;
            }

            if (in_array($attribute_name, $unique_attributes))
            {
                $previous_object = $className::find(array('conditions' => ["$attribute_name = ?", $this->request->getParam($request_param_key)]));

                if (!empty($previous_object)) {
                    ApiException::custom('duplicate_entry', '400 Invalid', 'Duplicate object attribute found for '.$attribute_name);
                }
            }

            $object->$attribute_name = $this->request->getParam($request_param_key);
        }

        dispatch_event('auto_rest_api_before_model_create', $object);

        $object->save();

        dispatch_event('auto_rest_api_after_model_create', $object);

        ViewController::displayModel($object, $modelDescription);

    }


}