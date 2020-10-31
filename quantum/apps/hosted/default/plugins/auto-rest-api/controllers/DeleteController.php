<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ModelDescription;
use Quantum\ApiException;

class DeleteController extends \Quantum\Controller
{


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

        $model->delete();

        $this->output->adaptable(['status' => 'ok']);
    }

}
