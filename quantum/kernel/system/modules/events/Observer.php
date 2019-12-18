<?php

namespace Quantum\Events;

use Quantum\Serialize\Serializer\SerializedHash;

/**
 * Class Observer
 * @package Quantum\Events
 */
class Observer
{
    /**
     * @var callable
     */
    public $_callback;
    /**
     * @var bool
     */
    public $_hasBeenCalled;
    /**
     * @var bool
     */
    public $_callOnce;
    /**
     * @var string
     */
    public $_callbackHash;
    /**
     * @var bool
     */
    private $_shouldPassEvent;
    /**
     * @var bool
     */
    private $_shouldPassData;

    /**
     * Observer constructor.
     * @param $callback
     * @param bool $callOnlyOnce
     * @param bool $shouldPassEvent
     * @param bool $shouldPassData
     * @throws EventRegisterException
     */
    public function __construct($callback, $callOnlyOnce = false, $shouldPassEvent = true, $shouldPassData = true)
    {
        if (!is_callable($callback))
            throw new EventRegisterException("Event callback to register must be a callable ".gettype($callback). " given");


        $this->_callback = $callback;
        $this->_callOnce = $callOnlyOnce;
        $this->_hasBeenCalled = false;
        $this->_callbackHash  = self::createCallbackHash($callback);
        $this->_shouldPassEvent = $shouldPassEvent;
        $this->_shouldPassData = $shouldPassData;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->_callback;
    }

    /**
     * @param $event
     * @param $data
     */
    public function callCallback($event, $data)
    {
        if ($this->hasBeenCalled() && $this->shouldCallOnlyOnce())
            return;

        $function = $this->_callback;

        if ($this->_shouldPassEvent && $this->_shouldPassData)
        {
            $function($event, $data);
        }
        elseif (!$this->_shouldPassEvent && $this->_shouldPassData)
        {
            $function($data);
        }
        elseif ($this->_shouldPassEvent && !$this->_shouldPassData)
        {
            $function($event);
        }
        elseif (!$this->_shouldPassEvent && !$this->_shouldPassData)
        {
            $function();
        }

        $this->setHasBeenCalled (true);
    }

    /**
     * @return bool
     */
    public function hasBeenCalled()
    {
        return $this->_hasBeenCalled;
    }

    /**
     * @param $called
     */
    public function setHasBeenCalled($called)
    {
        $this->_hasBeenCalled = $called;
    }

    /**
     * @return bool
     */
    public function shouldCallOnlyOnce()
    {
        return $this->_callOnce;
    }

    /**
     * @return string
     */
    public function  getCallbackHash()
    {
        return $this->_callbackHash;
    }

    /**
     * @param $callback
     * @return string
     */
    public static function createCallbackHash($callback)
    {
        return SerializedHash::hashCallable($callback);
    }

}

