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

        if (qs($id)->isUuid())
        {
            $model = $modelName::find(array('conditions' => array("uuid = ?", $id)));
        }
        elseif (qs($id)->isNumber())
        {
            $model = $modelName::find(array('conditions' => array("id = ?", $id)));
        }

        if (empty($model)) {
            ApiException::resourceNotFound();
        }

        $this->displayModel($model, $modelDescription);
    }

    public function displayModel($model, ModelDescription $modelDescription)
    {
        $visible_attributes = $modelDescription->getVisibleAttributes();

        $datum = new_vt();

        foreach ($visible_attributes as $attribute_name => $value)
        {
            if (qs($value)->contains('()')) {
                $value = call_user_func([$model, qs($value)->removeCharacters('()')->toStdString()]);
            }
            else {
                $value = $model->$value;
            }

            $datum->set($attribute_name, $value);
        }

        $this->output->adaptable($datum);
    }


}
