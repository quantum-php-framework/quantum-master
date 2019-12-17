<?php

namespace Quantum\Cache;

use Quantum\Serialize\Native;

/**
 * Class EncryptedFileBasedCacheStorage
 * @package Quantum\Cache
 */
class Database extends Backend
{

    /**
     * Database constructor.
     */
    public function __construct()
    {
        parent::__construct();
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

        $expiration = time()+$expiration;

        $data = Native::serialize($var);

        $item = \CacheItem::find_by_key($key);

        if (empty($item))
        {
            $item = new \CacheItem();
            $item->key = $key;
            $item->data = $data;
            $item->expiration = $expiration;
            $item->save();
        }
        else
        {
            $item->data = $data;
            $item->save();
        }

        return $var;

    }

    /**
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        $item = \CacheItem::find_by_key($key);

        if (empty($item))
            return false;

        $data = Native::unserialize($item->data);

        if (empty($data))
        {
            $item->delete();
            return false;
        }

        $t = time();

        if ($t > $item->expiration)
        {
            $item->delete();
            return false;
        }

        return $data;
    }

    /**
     * @return mixed|void
     */
    public function flush()
    {
        $items = \CacheItem::all();

        foreach ($items as $item)
        {
            $item->delete();
        }
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    public function delete($key)
    {
        $item = \CacheItem::find_by_key($key);

        if (empty($item))
            return false;

        $item->delete();
    }



}