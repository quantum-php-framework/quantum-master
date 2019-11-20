<?php


/**
 * Class CrudInterface
 */
abstract class CrudInterface
{
    /**
     * @var
     */
    public $_modelName;
    /**
     * @var
     */
    public $_requiredParams;
    /**
     * @var
     */
    public $_additionalParams;
    /**
     * @var
     */
    public $_callback;

    /**
     * CrudInterface constructor.
     * @param $modelName
     */
    public function __construct($modelName)
    {
        $this->_modelName = $modelName;
    }

    /**
     * @return mixed
     */
    public function getModelName()
    {
        return $this->_modelName;
    }

    /**
     * @param $params
     */
    public function setRequiredParams($params)
    {
        $this->_requiredParams = $params;
    }

    /**
     * @param $params
     */
    public function setAdditionalParams($params)
    {
        $this->_additionalParams = $params;
    }

    /**
     * @param $callback
     */
    public function setCallback($callback)
    {
        $this->_callback = $callback;
    }

    /**
     * @return mixed
     */
    public function getRequiredParams()
    {
        return $this->_requiredParams;
    }

    /**
     * @return mixed
     */
    public function getAdditionalParams()
    {
        return $this->_additionalParams;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->_callback;
    }

    /**
     * @param $model
     */
    public function callCallbackIfNeeded($model)
    {
        if (isset($this->_callback) && is_closure($this->_callback))
        {
            $function = $this->getCallback();
            $function($model);
        }
    }


    /**
     * @param $request
     * @return mixed
     */
    abstract protected function processRequest($request);


    /**
     * @param $modelName
     * @param array $additionalParams
     * @param array $requiredParams
     * @param null $closure
     * @return mixed|null
     */
    public static function create($modelName, array $additionalParams = array(), array $requiredParams = array(), $closure = null)
    {
        $interface = new CreateInterface($modelName);
        $interface->setAdditionalParams($additionalParams);
        $interface->setRequiredParams($requiredParams);
        $interface->setCallback($closure);

        return $interface->processRequest(QM::request());

    }

    /**
     * @param \ActiveRecord\Model $model
     * @param array $additionalParams
     * @param array $requiredParams
     * @param null $closure
     * @return mixed|null
     */
    public static function edit(ActiveRecord\Model $model, array $additionalParams = array(), array $requiredParams = array(), $closure = null)
    {
        $interface = new EditInterface($model);
        $interface->setAdditionalParams($additionalParams);
        $interface->setRequiredParams($requiredParams);
        $interface->setCallback($closure);

        return $interface->processRequest(QM::request());
    }

    /**
     * @param $modelName
     * @param array $requiredParams
     * @param null $closure
     * @return mixed|null
     */
    public static function delete($modelName, array $requiredParams = array(), $closure = null)
    {
        $interface = new DeleteInterface($modelName);
        $interface->setRequiredParams($requiredParams);
        $interface->setCallback($closure);

        return $interface->processRequest(QM::request());
    }









}