ç<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 12/26/18
 * Time: 2:40 AM
 */
class DeleteInterface extends CrudInterface
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
            return null;

        $modelName = $this->getModelName();

        if (is_string($modelName))
        {
            $id = $request->getPostParam(qs($modelName)->toLowerCase().'_id');

            $modelName = qs($modelName)->upperCaseFirst()->toStdString();

            if (qs($id)->isUuid())
            {
                $model = $modelName::find(array('conditions' => array("uuid = ?", $id)));

                if (empty($model))
                    return null;
            }

            if (qs($id)->isNumber())
            {
                $model = $modelName::find(array('conditions' => array("id = ?", $id)));

                if (empty($model))
                    return null;
            }
        }
        elseif (is_object($modelName))
        {
            if (instance_of($modelName, \ActiveRecord\Model::class) || instance_of($modelName, \Quantum\ActiverecordModel::class))
                $model = $modelName;
            else
                throw_exception('Object passed is not an instance of ActiveRecord model');
        }


        if (method_exists($model, 'destroy'))
        {
            $model->destroy();
        }
        else
        {
            $model->delete();
        }

        $acting_user = Auth::getUserFromSession();

        if ($acting_user)
        {
            $model_uri = qs($modelName)->toLowerCase();
            $a = new UserActivity();
            $a->user_id = $acting_user->id;
            $a->type = "delete_".$model_uri;
            $a->data = json_encode(["id" => $model->id, 'model' => $model->to_json()]);
            $a->save();

        }

        $o = new DeletedObject();
        $o->model_name = $modelName;
        $o->data = $model->to_json();
        if ($acting_user)
        {
            $o->account_id = $acting_user->account_id;
            $o->user_id = $acting_user->id;
        }
        $o->save();

        $this->callCallbackIfNeeded($model);

        return $model;
    }



}