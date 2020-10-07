<?php

namespace Quantum;

use Quantum\Serialize\Serializer\Json;
use Quantum\Serialize\Serializer\Native;

/**
 * @property  app_name
 * @property  properties
 */
class ActiveAppFileDatabaseStorage extends \Quantum\Cache\Backend\FilesBasedCacheStorage
{

    public function __construct($app_name = null, $encrypt = false)
    {
        parent::__construct();

        $this->setFileExtension('.txt');
        $this->setDirName('encrypted');

        if (!$app_name) {
            $app_name = get_active_app_setting('name');
        }

        $this->app_name = $app_name;
        $this->encrypt = $encrypt;

    }


    public function set($key, $var, $expiration = null)
    {
        $data = serialize($var);

        if ($this->encrypt)
        {
            $data = SystemEncryptor::encrypt($data);

            $this->getFile($key)->writeLocked($data)->compress();
        }
        else
        {
            $this->getFile($key)->writeLocked($data);
        }

        return $var;
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



        if ($this->encrypt)
        {
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
        }
        else
        {
            $data = $file->getContents();

        }

        $data = unserialize($data);



        if (!$data)
        {
            $file->delete();
            return false;
        }

        //dd($data);

        return $data;
    }


    /**
     * @param $key
     */
    public function getFile($key)
    {
        $filename = qs($key)->sha1();

        $dir = $this->getStorageDir()->getChildFile($filename->getFirstCharacter().$filename->getLastCharacter());

        if (!$dir->isDirectory())
            $dir->create();

        $file = $dir->getChildFile($filename->append($this->getFileExtension()));

        return $file;
    }

    /**
     * @return File
     */
    public function getStorageDir()
    {
        $ipt = InternalPathResolver::getInstance();
        $dir = File::newFile($ipt->getLocalDbRoot())->getChildFile($this->getDirName())->getChildFile($this->app_name);

        if (!$dir->isDirectory())
            $dir->create();

        return $dir;

    }

}