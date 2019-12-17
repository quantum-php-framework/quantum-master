<?php

namespace Quantum\Serialize;

/**
 * Class Serializer
 * @package Quantum\Serialize
 */
class Json
{
    /**
     * Serializer constructor.
     */
    function __construct (){}

    /**
     * @param $data
     * @return false|string
     */
    public static function serialize($data)
    {
        $serialized = \json_encode($data);

        return $serialized;
    }


    /**
     * @param $data
     * @return mixed
     */
    public static function unserialize($data)
    {
        $unserialized = \json_decode($data);

        return $unserialized;
    }


}
    
    
    
   
    
    
    
