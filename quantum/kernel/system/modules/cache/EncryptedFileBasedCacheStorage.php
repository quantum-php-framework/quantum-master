<?php

namespace Quantum\Cache;

use Quantum\SystemEncryptor;

/**
 * Class EncryptedFileBasedCacheStorage
 * @package Quantum\Cache
 */
class EncryptedFileBasedCacheStorage extends FilesBasedCacheStorage
{

    /**
     * @param $key
     * @param $var
     * @param int $expiration
     * @return mixed
     */
    public function set($key, $var, $expiration = 31556952)
    {
        $var = $this->encrypt($var);
        return parent::set($key, $var, $expiration);
    }

    /**
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        $data = parent::get($key);

        if (empty($data))
            return false;

        return $this->decrypt($data);
    }


    /**
     * @param $key
     * @param int $offset
     * @param int $initial_value
     * @param int $expiry
     * @return bool|int|string
     */
    public function increment($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        if ($this->has($key))
        {
            $value = $this->get($key) + $offset;
            $this->set($key, $value);
            return $value;

        }
        else
        {
            $this->set($key, $offset+$initial_value);
        }
    }


    /**
     * @param $key
     * @param int $offset
     * @param int $initial_value
     * @param int $expiry
     * @return bool|int|string
     */
    public function decrement($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        if ($this->has($key))
        {
            $value = $this->get($key) - $offset;
            $this->set($key, $value);
            return $value;

        }
        else
        {
            $this->set($key, $initial_value-$offset);
        }
    }


    /**
     * @param $data
     * @return string
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    private function encrypt($data)
    {
        return SystemEncryptor::encrypt($data);
    }

    /**
     * @param $data
     * @return string
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    private function decrypt($data)
    {
        return SystemEncryptor::decrypt($data);
    }
}