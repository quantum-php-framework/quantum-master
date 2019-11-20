<?php

namespace Quantum\Events;

/**
 * Class Event
 * @package Quantum\Events
 */
class Event
{
    /**
     * @var \Quantum\ValueTree
     */
    private $observers;
    /**
     * @var \Quantum\ValueTree
     */
    private $properties;

    /**
     * @var string
     */
    public $name;
    /**
     * @var
     */
    public $data;


    /**
     * @param $name
     * @return Event
     */
    public static function create($name)
    {
        $e = new Event($name);

        return $e;
    }

    /**
     * Event constructor.
     * @param string $name
     */
    public function __construct($name = "")
    {
        $this->observers  = new_vt();
        $this->properties = new_vt();

        if (!empty($name))
            $this->name = $name;
    }

    /**
     * @param $callback
     * @param $callOnlyOnce
     * @throws EventRegisterException
     */
    public function add($callback, $callOnlyOnce, $shouldPassEvent = true, $shouldPassData = true)
    {
        if ($this->hasCallback($callback))
            throw_exception("Callback already added to event");

        $this->observers->add(new Observer($callback, $callOnlyOnce, $shouldPassEvent, $shouldPassData));
    }


    /**
     * @param $data
     */
    public  function notifyListeners($data)
    {
        $this->data = $data;

        foreach ($this->observers->all() as $observer)
        {
            $observer->callCallback($this, $data);
        }
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return \Quantum\Request::getInstance();
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return \Quantum\Output::getInstance();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $callback
     * @return bool
     */
    public function detach ($callback)
    {
        foreach ($this->observers->all() as $key => $observer)
        {
            if ($observer->getCallbackHash() == Observer::createCallbackHash($callback))
                $this->observers->remove($key);
        }

        return false;
    }


    /**
     * @param $callback
     * @return bool
     */
    public function hasCallback($callback)
    {
        foreach ($this->observers->all() as $observer)
        {
            if  ($observer->getCallbackHash() == Observer::createCallbackHash($callback))
                return true;
        }

        return false;
    }
}

