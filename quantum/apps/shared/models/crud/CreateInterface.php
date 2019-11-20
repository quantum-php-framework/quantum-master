<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 12/26/18
 * Time: 2:40 AM
 */
class CreateInterface extends CrudInterface
{
    public function __construct($modelName)
    {
        parent::__construct($modelName);
    }

    function processRequest($request)
    {
        if (!$request->isPost())
            return null;

        $params = $request->getPostParams();

        if (empty($params))
            return null;

        if ($request->isMissingParams($this->getRequiredParams()))
            trigger_error("missing required params");

        $additional_params = $this->getAdditionalParams();

        if (!empty($additional_params))
            $params = array_merge($params, $additional_params);


        $model = $this->createModelWithParams($params);

        $this->callCallbackIfNeeded($model);

        return $model;
    }

    function createModelWithParams($params)
    {
        $modelName = $this->getModelName();
        $model = new $modelName();
        $model_attributes = $model->attributes();

        foreach ($params as $key => $param)
        {
            if (array_key_exists($key, $model_attributes))
                $model->assign_attribute($key, $param);
        }

        $model->save();

        $acting_user = Auth::getUserFromSession();

        if ($acting_user)
        {
            $model_uri = qs($modelName)->toLowerCase();
            $a = new UserActivity();
            $a->user_id = $acting_user->id;
            $a->type = "create_".$model_uri;
            $a->data = json_encode(["id" => $model->id, 'model' => $model->to_json()]);
            $a->save();
        }

        return $model;
    }



}