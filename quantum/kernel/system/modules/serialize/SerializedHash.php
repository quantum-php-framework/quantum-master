<?php

namespace Quantum\Serialize;

/**
 * Class SerializedHash
 * @package Quantum\Serialize
 */
class SerializedHash
{
    /**
     * SerializedHash constructor.
     */
    function __construct (){}

    /**
     * @param $data
     * @param string $algo
     * @return string
     */
    public static function hash($data, $algo = "md5")
    {
        $serialized = \Quantum\Serialize\Serializer::serialize($data);

        return hash($algo, $serialized);
    }

    /**
     * @param $closure
     * @param string $algo
     * @return string
     */
    public static function hashClosure($closure, $algo = "md5")
    {
        $serialized = \Quantum\Serialize\Serializer::serializeClosure($closure);

        return hash($algo, $serialized);
    }

    /**
     * @param $callable
     * @param string $algo
     * @return string
     */
    public static function hashCallable($callable, $algo = "md5")
    {
        if (is_closure($callable))
            return self::hashClosure($callable);

        return self::hash($callable);
    }


}
    
    
    
   
    
    
    
