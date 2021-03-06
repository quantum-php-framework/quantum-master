<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ModelDescription;
use Quantum\ApiException;
use Quantum\Controller;

class DeleteController extends Controller
{


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

        dispatch_event('auto_rest_api_before_model_delete', $model);
        dispatch_event('auto_rest_api_before_'.$modelDescription->getSingularForm().'_delete', $model);

        $model->delete();

        dispatch_event('auto_rest_api_after_model_delete', $model);
        dispatch_event('auto_rest_api_after_'.$modelDescription->getSingularForm().'_delete', $model);

        return ['status' => 'ok'];
    }

}
