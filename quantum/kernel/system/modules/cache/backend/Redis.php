<?php

namespace Quantum\Cache\Backend;

use Quantum\Cache\Backend;
use Quantum\Config;
use Predis\Client;
use Quantum\Cache\StorageSetupException;

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
     *
     */
    const CRYPTO_SCHEME = 'shared-crypto://';


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

        $this->init($config->get('redis_scheme'),
                    $config->get('redis_host'),
                    $config->get('redis_port'),
                    $config->get('redis_persistent'),
                    $config->get('redis_password'));
    }

    /**
     * @param $scheme
     * @param $host
     * @param $port
     * @param $persistent
     * @param bool $password
     */
    public function init($scheme, $host, $port, $persistent, $password = false)
    {
        $redis_config = array(
            "scheme" => $scheme,
            "host" => $host,
            "port" => $port,
            'persistent' => $persistent);

        if (!empty($password))
            $redis_config['redis_password'] = $password;

        $this->redis = new Client($redis_config);
    }


    /**
     * @param $key
     * @param $var
     * @param int $expiration
     * @return mixed
     */
    public function set($key, $value, $expiration = 0)
    {
        $this->redis->set($key, maybe_serialize($value));
        $this->setExpiration($key, $expiration);
        return $value;
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
        $value = $this->redis->get($key);

        if (!empty($value))
            $value = maybe_unserialize($value);

        return $value;
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

    /**
     * @param $queue
     * @param $item
     * @return bool
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function pushToListEncrypted($queue, $item)
    {
        if (empty($queue) || empty($item))
            return false;

        $scheme = self::CRYPTO_SCHEME;

        $data = serialize($item);

        $key = $this->getSharedEncryptionKey();

        $data = \Quantum\Crypto::encrypt($data, $key);

        $string = $scheme.$data;

        return $this->pushToList($queue, $string, false);
    }

    /**
     * @param $queue
     * @param $item
     * @param bool $serialize
     * @return bool
     */
    public function pushToList($queue, $item, $serialize = true)
    {
        if ($serialize)
            $item = serialize($item);

        $length = $this->redis->rpush($queue, $item);
        if ($length < 1) {
            return false;
        }
        return true;
    }


    /**
     * @return |null
     */
    private function getSharedEncryptionKey()
    {
        $conf =  \Quantum\Config::getInstance();

        if ($conf)
        {
            $env = $conf->getEnvironment();

            if (!empty($env))
            {
                if (isset($env->shared_encryption_key))
                    return $env->shared_encryption_key;
            }
        }

        $conf = \Quantum\Qubit::getConfig();

        if (isset($conf->shared_encryption_key))
            return $conf->shared_encryption_key;

        return null;
    }

    /**
     * @param $listname
     * @return mixed|null
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function popFromList($listname)
    {
        $item = $this->redis->lpop($listname);

        if(!$item) {
            return null;
        }

        if (qs($item)->startsWith(self::CRYPTO_SCHEME))
        {
            $key = $this->getSharedEncryptionKey();

            if ($key)
                return unserialize(\Quantum\Crypto::decrypt(qs($item)->fromLastOccurrenceOf('/')->toStdString(), $key));
        }
        else
        {
            return unserialize($item);
        }
    }

    /**
     * @param $source
     * @param $dest
     * @return mixed|null
     */
    public function popAndPush($source, $dest)
    {
        $item = $this->redis->rpoplpush($source, $dest);

        if(!$item) {
            return null;
        }

        return unserialize($item);
    }

    /**
     * @param $listname
     * @return int
     */
    public function getListLength($listname)
    {
        return $this->redis->llen($listname);
    }

    /**
     * @param $listname
     * @return array
     */
    public function getList($listname)
    {
        return $this->redis->lrange($listname, 0, $this->getListLength($listname));
    }

    /**
     * @param $listname
     * @return int
     */
    public function flushList($listname)
    {
        return $this->redis->del($listname);
    }

    /**
     * @return mixed
     */
    public function isAvailable()
    {
        return $this->redis->ping();
    }


}