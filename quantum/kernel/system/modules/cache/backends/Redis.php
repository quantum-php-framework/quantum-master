<?php

namespace Quantum\Cache;

use Quantum\Config;
use Predis\Client;

/**
 * Class Storage
 * @package Quantum\Redis
 */
class Redis extends Backend
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
        if ($initFromEnv)
            $this->initFromEnvironmentConfig();
    }

    /**
     * @throws \Exception
     */
    public function initFromEnvironmentConfig()
    {
        $config = new_vt(Config::getInstance()->getEnvironment());

        if (empty($config))
            throw new StorageSetupException('no active app config');

        if (!$config->has('redis_scheme'))
            throw new StorageSetupException('redis_scheme not defined in app config');

        if (!$config->has('redis_host'))
            throw new StorageSetupException('redis_host not defined in app config');

        if (!$config->has('redis_port'))
            throw new StorageSetupException('redis_port not defined in app config');

        if (!$config->has('redis_persistent'))
            throw new StorageSetupException('redis_persistent not defined in app config');

        $redis_config = array(
            "scheme" => $config->get('redis_scheme'),
            "host" => $config->get('redis_host'),
            "port" => $config->get('redis_port'),
            'persistent' => $config->get('redis_persistent'));

        if ($config->has('redis_password'))
            $redis_config['redis_password'] = $config->get('redis_password');

        $this->redis = new Client($redis_config);
    }


    /**
     * @param $key
     * @param $var
     * @param int $expiration
     * @return mixed
     */
    public function set($key, $var, $expiration = 0)
    {
        $this->redis->set($key, $var);
        $this->setExpiration($key, $expiration);
        return $var;
    }

    /**
     * @param $items
     * @param int $expiration
     * @return bool
     */
    public function setParams($items)
    {
        return $this->redis->mset($items);
    }


    /**
     * @param $key
     * @return string
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * @param $key
     * @return int
     */
    public function has($key)
    {
        return $this->redis->exists($key);
    }


    /**
     * @return Client
     */
    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * @return bool
     */
    public function flush()
    {
        return $this->redis->flushall();
    }

    /**
     * @param $key
     * @param int $time
     * @return bool
     */
    public function delete($key)
    {
        return $this->redis->del((array)$key);
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
            $this->set($key, $value, $expiry);
            return $value;
        }

        return $this->redis->incrby($key, $offset);
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
            $value = $initial_value+$offset;
            $this->set($key, $value, $expiry);
            return $value;
        }

        return $this->redis->decrby($key, $offset);
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