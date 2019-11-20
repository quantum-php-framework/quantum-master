<?php

namespace Quantum;

use GeoIp2\Database\Reader;

/**
 * Class MaxmindGeoIp
 * @package Quantum
 */
class MaxmindGeoIp
{
    /**
     * @var
     */
    public $reader;
    /**
     * @var
     */
    public $record;

    /**
     * MaxmindGeoIp constructor.
     * @param $ip
     * @param bool $fetch
     */
    function __construct($ip, $fetch = true)
    {
        $this->ip = $ip;
        $this->readDb();

    }

    /**
     * @throws \GeoIp2\Exception\AddressNotFoundException
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException
     */
    public function readDb()
    {
        $this->reader = new Reader(self::getDbFileIfNeeded());

        $this->record = $this->reader->country($this->ip);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function downloadDb()
    {
        $filename = "GeoLite2-Country.tar.gz";
        $db_filename = "GeoLite2-Country.mmdb";

        $url = "https://geolite.maxmind.com/download/geoip/database/".$filename;

        $request = new CurlRequest($url);
        $request->execute();

        $data = $request->getResponse();

        $etc = new File(InternalPathResolver::getInstance()->etc_root);

        $geoip_location = $etc->getChildFile('geoip');

        if ($geoip_location->exists())
            $geoip_location->delete();

        $db_location = $geoip_location->getChildFile('db');

        $compressed_file_location = $db_location->getChildFile($filename);
        $compressed_file_location->replaceContent($data);

        $phar = new \PharData(($compressed_file_location->getPath()));

        $phar->extractTo($db_location->getParentDirPath());

        if ($geoip_location->containsSubDirectories())
        {
            $dbFile = $geoip_location->findFileInChildDirectories($db_filename);

            if ($dbFile->exists())
            {
                $target = $db_location->getChildFile($db_filename)->getPath();
                $dbFile->move($target);
                $geoip_location->deleteAllChildFilesExcept($db_filename);
                return $target;
            }

        }


    }

    /**
     * @return File
     */
    public static function getDbFile()
    {
        $etc = new File(InternalPathResolver::getInstance()->etc_root);

        $geoip_location = $etc->getChildFile('geoip');

        $db_location = $geoip_location->getChildFile('db');

        return $db_location->getChildFile("GeoLite2-Country.mmdb");
    }

    /**
     * @return bool|QString
     * @throws \Exception
     */
    public function getDbFileIfNeeded()
    {
        $file = self::getDbFile();

        if (!$file->exists())
            self::downloadDb();

        return $file->getRealPath();
    }


    /**
     * @param $ip
     * @return string
     */
    public static function getCountry($ip)
    {
        if (in_array($ip, Request::getLocalHostIps()))
            return "localhost";

        $geoip = new MaxmindGeoIp($ip);

        return $geoip->record->country->name;
    }


    /**
     * @param $ip
     * @return string
     */
    public static function getCountryCode($ip)
    {
        if (in_array($ip, Request::getLocalHostIps()))
            return "localhost";

        $geoip = new MaxmindGeoIp($ip);

        return $geoip->record->country->isoCode;
    }





}