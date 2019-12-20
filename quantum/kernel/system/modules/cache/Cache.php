<?php

namespace Quantum;


/**
 * Class Cache
 * @package Quantum
 */
class Cache
{

    /**
     * Set a value to a key
     * @param $key
     * @param $var
     * @param int $expiration
     * @return mixed
     */
    public static function set($key, $var, $expiration = 0)
    {
        $provider = self::getProvider();

        $val = $provider->set($key, $var, $expiration);

        return $val;
    }

    /**
     * Set a key only if it is missing and a callback is provided
     * returns the value set to the key by the callback
     * @param $key
     * @param $callback
     * @return mixed
     */
    public static function setDeferred($key, $callback)
    {
        $provider = self::getProvider();

        if (!$provider->has($key) && is_callable($callback))
        {
            $provider->set($key, $callback());
        }

        return $provider->get($key);
    }

    /**
     * Set a value to a key provided from a callback call
     * @param $key
     * @param $callback
     * @return mixed
     */
    public static function setWithCallback($key, $callback)
    {
        $provider = self::getProvider();

        return $provider->set($key, $callback());
    }



    /**
     * Get a value  previously set by the provided key.
     * If the value is not found and the fallback var is provided
     * it will be set to it.
     * The fallback can be a callable which must return a value.
     * @param $key
     * @return mixed
     */
    public static function get($key, $fallback = null)
    {
        $provider = self::getProvider();

        if (!$provider->has($key))
        {
            if (is_callable($fallback))
            {
                $provider->set($key, $fallback());
            }

            if (!is_callable($fallback) && !is_null($fallback))
            {
                $provider->set($key, $fallback);
            }
        }

        return $provider->get($key);
    }

    /**
     * @param $key
     * @param $var
     * @return mixed
     */
    public static function replace($key, $var)
    {
        return self::getProvider()->replace($key, $var);
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function delete($key)
    {
        $provider = self::getProvider();

        return $provider->delete($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function has($key)
    {
        $provider = self::getProvider();

        return $provider->has($key);
    }

    /**
     * @return mixed
     */
    public static function flush()
    {
        $provider = self::getProvider();

        return $provider->flush();
    }

    /**
     * @param $items
     * @return mixed
     */
    public static function setParams($items)
    {
        $provider = self::getProvider();

        return $provider->setParams($items);
    }

    /**
     * @return mixed
     */
    public static function useRedis()
    {
        return self::setDriver('redis');
    }

    /**
     * @return mixed
     */
    public static function useMemcache()
    {
        return self::setDriver('memcache');
    }

    /**
     * @return mixed
     */
    public static function useFiles()
    {
        return self::setDriver('files');
    }

    /**
     * @return mixed
     */
    public static function useEncryptedFiles()
    {
        return self::setDriver('encrypted');
    }

    /**
     * @return mixed
     */
    public static function useAPC()
    {
        return self::setDriver('apc');
    }

    /**
     * @return mixed
     */
    public static function useEaccelerator()
    {
        return self::setDriver('eaccelerator');
    }

    /**
     * @return mixed
     */
    public static function useDatabase()
    {
        return self::setDriver('db');
    }

    /**
     * @return mixed
     */
    public static function useMongoDB()
    {
        return self::setDriver('mongodb');
    }


    /**
     * @param $driver
     * @return mixed
     */
    public static function setDriver ($driver)
    {
        $provider = self::getProvider();

        return $provider->setDriver($driver);
    }

    /**
     * @param $key
     * @param int $expiration
     * @return mixed
     */
    public function setExpiration ($key, $expiration = 0)
    {
        $provider = self::getProvider();

        return $provider->setExpiration($key, $expiration);
    }

    /**
     * @param $key
     * @param int $offset
     * @param int $initial_value
     * @param int $expiry
     * @return mixed
     */
    public static function increment($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        return self::getProvider()->increment($key, $offset, $initial_value, $expiry);
    }

    /**
     * @param $key
     * @param int $offset
     * @param int $initial_value
     * @param int $expiry
     * @return mixed
     */
    public static function decrement($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        return self::getProvider()->decrement($key, $offset, $initial_value, $expiry);
    }

    /**
     * @param $key
     * @param $callback
     * @return mixed
     */
    public static function incrementWithCallback($key, $callback)
    {
        return self::getProvider()->increment($key, $callback());
    }

    /**
     * @param $key
     * @param $callback
     * @return mixed
     */
    public static function decrementWithCallback($key, $callback)
    {
        return self::getProvider()->decrement($key, $callback());
    }

    /**
     * @return mixed
     */
    public static function getProvider()
    {
        return \Quantum\Cache\ServiceProvider::getInstance();
    }

    /**
     * @param $driver
     * @return mixed
     */
    public static function storage($driver)
    {
        return self::getProvider()->storage($driver);
    }

    /**
     * @param $driver
     * @return mixed
     */
    public function setBackend($driver)
    {
        return self::setDriver($driver);
    }

}