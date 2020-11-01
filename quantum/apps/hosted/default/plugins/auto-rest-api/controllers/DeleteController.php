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

        if (qs($id)->isUuid()) {
            $model = $modelName::find(array('conditions' => array("uuid = ?", $id)));
        }
        elseif (qs($id)->isNumber()) {
            $model = $modelName::find(array('conditions' => array("id = ?", $id)));
        }

        if (empty($model)) {
            ApiException::resourceNotFound();
        }

        dispatch_event('auto_rest_api_before_model_delete', $model);

        $model->delete();

        dispatch_event('auto_rest_api_after_model_delete', $model);

        $this->output->adaptable(['status' => 'ok']);
    }

}
