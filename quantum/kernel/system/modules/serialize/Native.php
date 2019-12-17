<?php

namespace Quantum\Serialize;

/**
 * Class Serializer
 * @package Quantum\Serialize
 */
class Native
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
        $serialized = @serialize($data);

        return $serialized;
    }


    /**
     * @param $data
     * @return mixed
     */
    public static function unserialize($data)
    {
        $unserialized = @unserialize($data);

        return $unserialized;
    }


}
    
    
    
   
    
    
    
