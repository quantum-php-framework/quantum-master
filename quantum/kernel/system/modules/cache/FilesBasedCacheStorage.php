<?php

namespace Quantum\Cache;

use Quantum\InternalPathResolver;
use Quantum\File;
use Quantum\Singleton;

/**
 * Class Storage
 * @package Quantum\Redis
 */
class FilesBasedCacheStorage extends Singleton
{

    /**
     * @var
     */
    private $file_extension;

    /**
     * @var
     */
    private $dir_name;

    /**
     * Storage constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setFileExtension('.cache');
        $this->setDirName('regular');
    }


    /**
     * @param $key
     */
    public function getFile($key)
    {
        $filename = qs($key)->sha1()->append($this->getFileExtension());

        $dir = $this->getStorageDir()->getChildFile(qs($filename)->getFirstCharacter()->prepend('cache-'));

        if (!$dir->isDirectory())
            $dir->create();

        $file = $dir->getChildFile($filename);

        return $file;
    }

    /**
     * @return File
     */
    public function getStorageDir()
    {
        $ipt = InternalPathResolver::getInstance();
        $dir = File::newFile($ipt->getCacheRoot())->getChildFile($this->getDirName());

        if (!$dir->isDirectory())
            $dir->create();

        return $dir;

    }


    /**
     * Defaults to one year if no expiration is set
     * @param $key
     * @param $var
     * @param int $expiration
     * @return mixed
     */
    public function set($key, $var, $expiration = 0)
    {
        // Serializing along with the TTL

        if ($expiration === 0)
            $expiration = 31556952;

        //dd($expiration);

        $data = serialize(array(time()+$expiration, $var));

        $this->getFile($key)->writeLocked($data)->compress();

        return $var;

    }

    /**
     * @param $items
     * @param int $expiration
     * @return bool
     */
    public function setParams($items)
    {
        foreach ($items as $key => $item)
        {
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
        $file = $this->getFile($key);

        if (!$file->exists())
            return false;

        $contents = $file->getContentsDecompressed();

        $data = @unserialize($contents);

        if (!$data)
        {
            $file->delete();
            return false;
        }

        $t = time();

        //expired time
        if ($t > $data[0])
        {
            $file->delete();
            return false;
        }

        return $data[1];
    }

    /**
     * @param $key
     * @return int
     */
    public function has($key)
    {
        return $this->getFile($key)->existsAsFile();
    }


    /**
     * @return bool
     */
    public function flush()
    {
        $this->getStorageDir()->delete();
        return $this->getStorageDir()->create();
    }

    /**
     * @param $key
     * @param int $time
     * @return bool
     */
    public function delete($key)
    {
        $file = $this->getFile($key);

        if ($file->existsAsFile())
            $file->delete();

        return $file;
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
           if (!$this->has($key))
               return false;

           $value = $this->get($key);

           $this->set($key, $value, $expiration);
        }
    }

    /**
     * @param $extension
     */
    public function setFileExtension($extension)
    {
        $this->file_extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getFileExtension()
    {
        return $this->file_extension;
    }


    /**
     * @param $name
     */
    public function setDirName($name)
    {
        $this->dir_name = $name;
    }

    /**
     * @return mixed
     */
    public function getDirName()
    {
        return $this->dir_name;
    }



}