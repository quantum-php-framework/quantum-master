<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ModelDescription;
use Quantum\ApiException;
use Quantum\Controller;
use Quantum\RequestParamValidator;

class UpdateController extends Controller
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

        $validator_rules = $modelDescription->getUpdateValidatorRules();

        if (!empty($validator_rules))
        {
            $validator = new RequestParamValidator();
            $validator->rules($validator_rules);

            if ($this->request->isPost())
            {
                if ($modelDescription->incomingUpdateParametersAreInFormData())
                {
                    if (!$validator->validatePost()) {
                        ApiException::custom('validation_errors', '200', json_encode($validator->getErrors()));
                    }
                }
                elseif ($modelDescription->incomingUpdateParametersAreInJsonBody())
                {
                    if (!$validator->validateJsonBodyParams('POST')) {
                        ApiException::custom('validation_errors', '200', json_encode($validator->getErrors()));
                    }
                }
            }
            elseif ($this->request->isPut())
            {
                if ($modelDescription->incomingUpdateParametersAreInFormData())
                {
                    if (!$validator->validatePut()) {
                        ApiException::custom('validation_errors', '200', json_encode($validator->getErrors()));
                    }
                }
                elseif ($modelDescription->incomingUpdateParametersAreInJsonBody())
                {
                    if (!$validator->validateJsonBodyParams('PUT')) {
                        ApiException::custom('validation_errors', '200', json_encode($validator->getErrors()));
                    }
                }
            }
        }

        $unique_attributes = $modelDescription->getUniqueAttributes();
        $editable_attributes = $modelDescription->getEditableAttributes();

        foreach ($editable_attributes as $attribute_name => $request_param_key)
        {
            if ($modelDescription->incomingUpdateParametersAreInFormData())
            {
                if ($this->request->isMissingParam($request_param_key)) {
                    continue;
                }

                if (in_array($attribute_name, $unique_attributes))
                {
                    $previous_object = $modelName::find(array('conditions' => ["$attribute_name = ?", $this->request->getParam($request_param_key)]));

                    if (!empty($previous_object) && $previous_object->$id_attribute != $model->$id_attribute) {
                        ApiException::custom('duplicate_entry', '400 Invalid', 'Duplicate object attribute found for '.$attribute_name);
                    }
                }

                $model->$attribute_name = $this->request->getParam($request_param_key);
            }
            elseif ($modelDescription->incomingUpdateParametersAreInJsonBody())
            {
                if (!$this->request->hasJsonBodyParam($request_param_key)) {
                    continue;
                }

                if (in_array($attribute_name, $unique_attributes))
                {
                    $previous_object = $modelName::find(array('conditions' => ["$attribute_name = ?", $this->request->getJsonBodyParam($request_param_key)]));

                    if (!empty($previous_object) && $previous_object->$id_attribute != $model->$id_attribute) {
                        ApiException::custom('duplicate_entry', '400 Invalid', 'Duplicate object attribute found for '.$attribute_name);
                    }
                }

                $model->$attribute_name = $this->request->getJsonBodyParam($request_param_key);
            }
        }

        dispatch_event('auto_rest_api_before_model_update', $model);
        dispatch_event('auto_rest_api_before_'.$modelDescription->getSingularForm().'_update', $model);

        $model->save();

        dispatch_event('auto_rest_api_after_model_update', $model);
        dispatch_event('auto_rest_api_after_'.$modelDescription->getSingularForm().'_update', $model);

        return ViewController::genVisibleData($model, $modelDescription);
    }



}