<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 12/26/18
 * Time: 2:40 AM
 */
class EditInterface extends CrudInterface
{
    public $_model;
    public function __construct($model)
    {
       $this->_model = $model;
       parent::__construct(get_class($model));
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


        $model = $this->_model;
        $model_attributes = $model->attributes();

        $previous_model_json = $model->to_json();

        foreach ($params as $key => $param)
        {
            if (array_key_exists($key, $model_attributes))
                $model->assign_attribute($key, $param);
        }
        $model->save();

        $after_model_json = $model->to_json();

        $acting_user = Auth::getUserFromSession();

        if ($acting_user)
        {
            $model_stub = qs(get_class($model))->toLowerCase();
            $a = new UserActivity();
            $a->user_id = $acting_user->id;
            $a->type = "edit_".$model_stub;
            $a->data = json_encode([
                "id" => $model->id,
                'model' => $model->to_json(),
                'before_model_state' => $previous_model_json,
                'after_model_state' => $after_model_json]);
            $a->save();
        }

        $this->callCallbackIfNeeded($model);

        return $model;
    }



}