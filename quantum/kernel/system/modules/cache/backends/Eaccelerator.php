<?php

namespace Quantum\Cache;

use Quantum\Serialize\Native;

/**
 * Class EncryptedFileBasedCacheStorage
 * @package Quantum\Cache
 */
class Eaccelerator extends Backend
{

    /**
     * Eaccelerator constructor.
     * @throws StorageSetupException
     */
    public function __construct()
    {
        if (!extension_loaded('eaccelerator')) {
            throw new StorageSetupException('Eaccelerator extension must be loaded for using this cache storage');
        }
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

        $data = array(time()+$expiration, $var);

        $data = Native::serialize($data);

        \eaccelerator_put($key, $data, $expiration);

        return $var;

    }

    /**
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
       $contents = \eaccelerator_get($key);

       if (!$contents)
           return false;

        $data = Native::unserialize($contents);

        if (!$data)
        {
            $this->delete($key);
            return false;
        }

        $t = time();

        if ($t > $data[0])
        {
            $this->delete($key);
            return false;
        }

        return $data[1];
    }

    /**
     * @return mixed
     */
    public function flush()
    {
        return \eaccelerator_clean();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {
        return \eaccelerator_rm($key);
    }



}