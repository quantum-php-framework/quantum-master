<?php

namespace OrderImport;

class MagentoServer extends \Quantum\ActiverecordModel
{
    static $table_name = 'oim_magento_servers';


    public function getWSDLUrl()
    {
        return $this->base_url.'api/?wsdl';
    }

    public function getStoresFromApiCached()
    {
        $key = 'MagentoServer:CachedStores';

        if (!\Quantum\Cache::has($key))
        {
            \Quantum\Cache::set($key, $this->getStoresFromApi(), 300);
        }

        return \Quantum\Cache::get($key);
    }

    public function getStoresFromApi()
    {
        $mage_server = $this;

        $soap_url = $mage_server->getWSDLUrl();

        $client = new \SoapClient($soap_url, ['trace' => 1]);

        try {
            $session = $client->login($mage_server->api_user, $mage_server->api_key);
        } catch (\Exception $exception) {
            //dd($exception);
            \Quantum\Logger::custom('Error connecting to: '.$soap_url." - Error (OXAFF01):". $exception->getMessage(), 'mage_connect_errors');
            //exit;
            return array();
        }

        // get attribute set
        $stores = $client->call($session, 'store.list');

        return $stores;
    }

    public function getStoresWebsitesIdsAsKeyPair()
    {
        $stores = $this->getStoresFromApiCached();

        $list = new_vt();

        foreach ($stores as $store)
        {
            $list->set($store['website_id'], $store['name']);

        }

        return $list->getArray();
    }

    public function reindexSingle($productId)
    {
        file_get_contents($this->base_url.'/reindexsingle.php?id='.$productId);
    }

}