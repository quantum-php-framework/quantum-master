<?php

namespace Quantum\Cache;

use Quantum\Singleton;

/**
 * Class ServiceProvider
 * @package Quantum\Cache
 */
class ServiceProvider extends Singleton
{

    /**
     * @var mixed
     */
    public $storage;

    /**
     * ServiceProvider constructor.
     */
    public function __construct()
    {
        $this->initRedis();
    }


    /**
     * @param $driver
     * @throws \Exception
     */
    public function setDriver ($driver)
    {
        switch ($driver)
        {
            case 'memcache':
                $this->initMemcache();
                break;

            case 'redis':
                $this->initRedis();
                break;
        }
    }

    /**
     * @param $key
     * @param $value
     * @param int $expiration
     * @return mixed
     */
    public function set($key, $value, $expiration = 0)
    {
        return $this->storage->set($key, $value, $expiration);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->storage->get($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function has($key)
    {
        return $this->storage->has($key);
    }


    /**
     * @param $key
     * @param int $offset
     * @param int $initial_value
     * @param int $expiry
     * @return mixed
     */
    public function increment($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        return $this->storage->increment($key, $offset, $initial_value, $expiry);
    }

    /**
     * @param $key
     * @param int $offset
     * @param int $initial_value
     * @param int $expiry
     * @return mixed
     */
    public function decrement($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        return $this->storage->decrement($key, $offset, $initial_value, $expiry);
    }

    /**
     * @param $items
     * @return mixed
     */
    public function setParams($items)
    {
        return $this->storage->setParams($items);
    }

    /**
     * @param $key
     * @param int $expiration
     * @return mixed
     */
    public function setExpiration ($key, $expiration = 0)
    {
        return $this->storage->setExpiration($key, $expiration);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {
        return $this->storage->delete($key);
    }

    /**
     * @param $key
     * @param $var
     * @return mixed
     */
    public function replace($key, $var)
    {
        return $this->storage->replace($key, $var);
    }


    /**
     * @throws \Exception
     */
    public function initMemcache()
    {
        $this->storage = new Memcache();
        return $this->getStorage();
    }

    /**
     * @throws \Exception
     */
    public function initRedis()
    {
        $this->storage = new Redis();
        return $this->getStorage();
    }

    /**
     * @return mixed
     */
    public function flush()
    {
        return $this->storage->flush();
    }

    /**
     * @return mixed
     */
    public function getStorage()
    {
        return $this->storage;
    }

}