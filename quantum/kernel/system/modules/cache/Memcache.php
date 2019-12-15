<?php


namespace Quantum\Cache;

use Quantum\Config;
use Quantum\Singleton;

/**
 * Class Storage
 * @package Quantum\Cache
 */
class Memcache extends Singleton
{

    /**
     * @var \Memcached
     */
    public $memcache;


    /**
     * Memcache constructor.
     * @param bool $initFromEnv
     * @throws \Exception
     */
    public function __construct($initFromEnv = true)
    {
        $this->memcache = new \Memcached();

        if ($initFromEnv)
            $this->initFromEnvironment();

    }

    /**
     * @throws \Exception
     */
    public function initFromEnvironment()
    {
        $config = new_vt(Config::getInstance()->getEnvironment());

        if (empty($config))
            throw_exception('no active app config');

        if (!$config->has('memcache_host'))
            throw_exception('memcache_host not defined in environment config');

        if (!$config->has('memcache_port'))
            throw_exception('memcache_port not defined in environment config');

        $this->addServer($config->get('memcache_host'), $config->get('memcache_port'));
        $this->enableBinaryProtocol();

        if ($config->has('memcache_username') && $config->has('memcache_password'))
        {

            $this->setSaslAuthData($config->get('memcache_username'), $config->get('memcache_password'));
        }
    }

    /**
     * @param $host
     * @param $port
     */
    public function addServer($host, $port)
    {
        $this->memcache->addServer($host, $port);
    }


    /**
     * @param $key
     * @param $var
     * @param int $expiration
     * @return bool
     */
    public function set($key, $var, $expiration = 0)
    {
        return $this->memcache->set($key, $var, $expiration);
    }


    /**
     * @param $items
     * @param int $expiration
     * @return bool
     */
    public function setParams($items, $expiration = 0)
    {
        return $this->memcache->setMulti($items, $expiration);
    }


    /**
     * @param $key
     * @param $var
     * @param int $expiration
     * @return bool
     */
    public function add($key, $var, $expiration = 0)
    {
        return $this->memcache->add($key, $var, $expiration);
    }


    /**
     * @param $key
     * @param $var
     * @param int $expiration
     * @return bool
     */
    public function replace($key, $var, $expiration = 0)
    {
        return $this->memcache->replace($key, $var, $expiration);
    }

    /**
     * @return int
     */
    public function getResultCode()
    {
        return $this->memcache->getResultCode();
    }

    /**
     * @return string
     */
    public function getResultMessage()
    {
        return $this->memcache->getResultMessage();
    }

    /**
     * @param $key
     * @return string
     */
    public function get($key)
    {
        return $this->memcache->get($key);
    }


    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->memcache->get($key) !== false;
    }


    /**
     * @return \Memcached
     */
    public function getMemcache()
    {
        return $this->memcache;
    }

    /**
     * @return bool
     */
    public function flush()
    {
        return $this->memcache->flush();
    }

    /**
     * @param $key
     * @param int $time
     * @return bool
     */
    public function delete($key, $time = 0)
    {
        return $this->memcache->delete($key, $time);
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
        return $this->memcache->increment($key, $offset, $initial_value, $expiry);
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
        return $this->memcache->decrement($key, $offset, $initial_value, $expiry);
    }

    /**
     * @return bool
     */
    public function quit()
    {
        return $this->memcache->quit();
    }

    /**
     * @param $key
     * @param int $expiration
     * @return bool
     */
    public function setExpiration($key, $expiration = 0)
    {
        return $this->memcache->touch($key, $expiration);
    }

    /**
     * @param bool $shouldBeEnabled
     * @return bool
     */
    public function enableBinaryProtocol($shouldBeEnabled = true)
    {
        return $this->memcache->setOption(\Memcached::OPT_BINARY_PROTOCOL, $shouldBeEnabled);
    }

    /**
     * @return array
     */
    public function getAllKeys()
    {
        return $this->memcache->getAllKeys();
    }

    /**
     * @param $keys
     * @param bool $with_cas
     * @param null $value_cb
     * @return mixed
     */
    public function getDelayed ($keys, $with_cas = true , $value_cb = null)
    {
        return $this->memcache->getDelayed((array)$keys, $with_cas, $value_cb);
    }


    /**
     * @return array
     */
    public function fetch()
    {
        return $this->memcache->fetch();
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        return $this->memcache->fetchAll();
    }

    /**
     * @return bool
     */
    public function isPersistent()
    {
        return $this->memcache->isPersistent();
    }


    /**
     * @return bool
     */
    public function isPristine()
    {
        return $this->memcache->isPristine();
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function setSaslAuthData ($username, $password)
    {
        return $this->memcache->setSaslAuthData($username, $password);
    }



}