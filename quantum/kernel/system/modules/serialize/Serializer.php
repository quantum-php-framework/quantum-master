<?php

namespace Quantum\Serialize;
use Opis\Closure\SerializableClosure;

/**
 * Class Serializer
 * @package Quantum\Serialize
 */
class Serializer
{
    /**
     * Serializer constructor.
     */
    function __construct (){}

    /**
     * @param $data
     * @return false|string
     */
    public static function serialize($data, $asJson = true)
    {
        if ($asJson)
            $serialized = \json_encode($data);
        else
            $serialized = @serialize($data);

        return $serialized;
    }


    /**
     * @param $data
     * @return mixed
     */
    public static function unserialize($data, $asJson = true)
    {
        if ($asJson)
            $unserialized = \json_decode($data);
        else
            $unserialized = @unserialize($data);

        return $unserialized;
    }

    /**
     * @param $closure
     * @return string
     */
    public static function serializeClosure($closure)
    {
        $wrapper = new SerializableClosure($closure);

        $s = \serialize($wrapper);

        return $s;
    }

    /**
     * @param $closure
     * @return mixed
     */
    public static function unserializeClosure($closure)
    {
        $wrapper = new SerializableClosure($closure);

        $s = \unserialize($wrapper);

        return $s;
    }


}
    
    
    
   
    
    
    
