<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ModelDescription;
use Quantum\ApiException;
use Quantum\Controller;
use Quantum\ControllerFactory;
use Quantum\RequestParamValidator;

class CreateController extends Controller
{
    function __construct()
    {

    }

    public function execute(ModelDescription $modelDescription)
    {
        $validator_rules = $modelDescription->getCreateValidatorRules();

        if (!empty($validator_rules))
        {
            $validator = new RequestParamValidator();
            $validator->rules($validator_rules);

            if ($modelDescription->incomingCreateParametersAreInFormData())
            {
                if (!$validator->validatePost()) {
                    ApiException::custom('validation_errors', '200', json_encode($validator->getErrors()));
                }
            }
            elseif ($modelDescription->incomingCreateParametersAreInJsonBody())
            {
                if (!$validator->validateJsonBodyParams('POST')) {
                    ApiException::custom('validation_errors', '200', json_encode($validator->getErrors()));
                }
            }
        }

        $className = $modelDescription->getClassName();

        $object = new $className();

        $unique_attributes = $modelDescription->getUniqueAttributes();
        $creatable_attributes = $modelDescription->getCreatableAttributes();

        foreach ($creatable_attributes as $attribute_name => $request_param_key)
        {
            if ($modelDescription->incomingCreateParametersAreInFormData())
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
            elseif ($modelDescription->incomingCreateParametersAreInJsonBody())
            {
                if (!$this->request->hasJsonBodyParam($request_param_key)) {
                    continue;
                }

                if (in_array($attribute_name, $unique_attributes))
                {
                    $previous_object = $className::find(array('conditions' => ["$attribute_name = ?", $this->request->getJsonBodyParam($request_param_key)]));

                    if (!empty($previous_object)) {
                        ApiException::custom('duplicate_entry', '400 Invalid', 'Duplicate object attribute found for '.$attribute_name);
                    }
                }

                $object->$attribute_name = $this->request->getJsonBodyParam($request_param_key);
            }
        }

        dispatch_event('auto_rest_api_before_model_create', $object);
        dispatch_event('auto_rest_api_before_'.$modelDescription->getSingularForm().'_create', $object);

        $object->save();

        dispatch_event('auto_rest_api_after_model_create', $object);
        dispatch_event('auto_rest_api_after_'.$modelDescription->getSingularForm().'_create', $object);

        return ViewController::genVisibleData($object, $modelDescription);

    }


}