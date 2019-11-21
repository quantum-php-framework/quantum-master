<?php


namespace Quantum\Redis;

use Predis\Client;
use Quantum\Config;
use Quantum\Singleton;

/**
 * Class Storage
 * @package Quantum\Redis
 */
class Storage extends Singleton
{

    /**
     * @var Client
     */
    public $redis;


    /**
     * Storage constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $config = new_vt(Config::getInstance()->getEnvironment());

        if (empty($config))
            throw_exception('no active app config');

        if (!$config->has('redis_scheme'))
            throw_exception('redis_scheme not defined in app config');

        if (!$config->has('redis_host'))
            throw_exception('redis_host not defined in app config');

        if (!$config->has('redis_port'))
            throw_exception('redis_port not defined in app config');

        if (!$config->has('redis_persistent'))
            throw_exception('redis_persistent not defined in app config');

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
     */
    public function set($key, $var)
    {
        $this->redis->set($key, $var);
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
     * @return Client
     */
    public function getClient()
    {
        return $this->redis;
    }



}