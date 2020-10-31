<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ModelDescription;
use Quantum\ControllerFactory;

class CreateController extends \Quantum\Controller
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

        $editable_attributes = $modelDescription->getEditableAttributes();

        foreach ($editable_attributes as $attribute_name => $request_param_key)
        {
            $object->$attribute_name = $this->request->getParam($request_param_key);
        }

        $object->save();

        $controller = ControllerFactory::create('AutoRestApi\Controllers\ViewController');
        $controller->displayModel($object, $modelDescription);

    }


}