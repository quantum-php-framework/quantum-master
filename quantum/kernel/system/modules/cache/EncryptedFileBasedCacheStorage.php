<?php

namespace Quantum\Cache;

use Quantum\SystemEncryptor;

/**
 * Class EncryptedFileBasedCacheStorage
 * @package Quantum\Cache
 */
class EncryptedFileBasedCacheStorage extends FilesBasedCacheStorage
{

    public function __construct()
    {
        parent::__construct();

        $this->setFileExtension('.enc');
        $this->setDirName('encrypted');
    }

    /**
     * @param $key
     * @param $var
     * @param int $expiration
     * @return mixed
     */
    public function set($key, $var, $expiration = 0)
    {
        if ($expiration === 0)
            $expiration = 31556952;

        $data = serialize(array(time()+$expiration, $var));

        $data = SystemEncryptor::encrypt($data);

        $this->getFile($key)->writeLocked($data)->compress();

    }

    /**
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        $file = self::getFile($key);

        if (!$file->existsAsFile())
            return false;

        $data = $file->getContentsDecompressed();

        if (!$data)
        {
            $file->delete();
            return false;
        }

        $data = SystemEncryptor::decrypt($data);

        if (!$data)
        {
            $file->delete();
            return false;
        }

        $data = @unserialize($data);

        if (!$data)
        {
            $file->delete();
            return false;
        }

        $t = time();

        if ($t > $data[0])
        {
            $file->delete();
            return false;
        }

        return $data[1];
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
}