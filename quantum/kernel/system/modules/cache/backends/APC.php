<?php

namespace Quantum\Cache;

/**
 * Class Storage
 * @package Quantum\Redis
 */
class APC extends Backend
{

    /**
     * Storage constructor.
     * @throws \Exception
     */
    public function __construct($initFromEnv = true)
    {

    }


    /**
     * @param $key
     * @param $var
     * @param int $expiration
     * @return mixed
     */
    public function set($key, $var, $expiration = 0)
    {
        $result = \apc_store($key, $var, $expiration);

        return $var;
    }

    /**
     * @param $key
     * @return string
     */
    public function get($key)
    {
        return \apc_fetch($key);
    }

    /**
     * @param $key
     * @return int
     */
    public function has($key)
    {
        return \apc_exists($key);
    }


    /**
     * @return bool
     */
    public function flush()
    {
        return \apc_clear_cache('user');
    }

    /**
     * @param $key
     * @param int $time
     * @return bool
     */
    public function delete($key)
    {
        return \apc_delete($key);
    }



}