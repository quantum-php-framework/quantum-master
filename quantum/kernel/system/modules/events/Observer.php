<?php

namespace Quantum\Events;

use FedEx\Reflection;
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
    public function __construct($callback, $callOnlyOnce = false, $shouldPassEvent = true, $shouldPassData = false)
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


    private function getCallableReflection($callable)
    {
        if(is_array($callable)) {
            $reflector = new \ReflectionMethod($callable[0], $callable[1]);
        } elseif(is_string($callable)) {
            $reflector = new \ReflectionFunction($callable);
        } elseif(is_a($callable, 'Closure') || is_callable($callable, '__invoke')) {
            $objReflector = new \ReflectionObject($callable);
            $reflector    = $objReflector->getMethod('__invoke');
        }

        return $reflector;

    }

    private function executeCallback($event = null)
    {
        $callable = $this->_callback;
        $reflector = $this->getCallableReflection($callable);
        $params_count = $reflector->getNumberOfParameters();

        if ($params_count === 0)
        {
            $data = $callable();
        }
        elseif ($params_count >= 1)
        {
            $param_type = $reflector->getParameters()[0]->getType();

            if ($param_type && $param_type->getName() === 'Quantum\Events\Event' && is_event($event)) {
                $data = $callable($event);
            }else {
                $data = $callable();
            }
        }

        $this->setHasBeenCalled (true);

        return $data;
    }

    /**
     * @param $event
     * @param $data
     */
    public function callCallback($event)
    {
        if ($this->hasBeenCalled() && $this->shouldCallOnlyOnce())
            return;

        $clone_event = clone $event;

        if (!$this->_shouldPassData) {
            $clone_event->data = null;
        }

        return $this->executeCallback($clone_event);
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

