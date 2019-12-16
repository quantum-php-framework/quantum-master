<?php


namespace Quantum\Cache;

use Predis\Client;
use Quantum\Config;
use Quantum\Singleton;

/**
 * Class Storage
 * @package Quantum\Redis
 */
class APC extends Singleton
{

    /**
     * @var Client
     */
    public $redis;


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

        $this->setExpiration($key, $expiration);

        return $result;
    }

    /**
     * @param $items
     * @param int $expiration
     * @return bool
     */
    public function setParams($items)
    {
        foreach ($items as $key => $item) {
            $this->set($key, $item);
        }
    }

    /**
     * @param $key
     * @param $var
     * @return mixed
     */
    public function add($key, $var)
    {
        return $this->set($key, $var);
    }

    /**
     * @param $key
     * @param $var
     * @param int $expiration
     * @return bool
     */
    public function replace($key, $var)
    {
        return $this->set($key, $var);
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


    /**
     * @param $key
     * @param int $offset
     * @param int $initial_value
     * @param int $expiry
     * @return int
     */
    public function increment($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        if (!$this->has($key))
        {
            $value = $initial_value+$offset;
            $this->set($key, $value);
            $this->setExpiration($expiry);
            return $value;
        }

        return $this->set($key, $this->get($key)+$offset);
    }


    /**
     * @param $key
     * @param int $offset
     * @param int $initial_value
     * @param int $expiry
     * @return int
     */
    public function decrement($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        if (!$this->has($key))
        {
            $value = $initial_value-$offset;
            $this->set($key, $value);
            $this->setExpiration($expiry);
            return $value;
        }

        return $this->set($key, $this->get($key)-$offset);
    }


    /**
     * @param $key
     * @param int $expiration
     */
    public function setExpiration ($key, $expiration = 0)
    {
        if ($expiration > 0)
        {
            $this->redis->expire($key, $expiration);
        }
    }



}