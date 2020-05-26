<?php

namespace OrderImport;

class MagentoImporter
{

    public static function fetchOrdersFromAllServers($from = '', $to = '', $id = '')
    {
        $operation = new ImportOperation();
        $operation->start_date = datestamp();
        $operation->save();


        $servers = MagentoServer::find_all_by_enabled(1);

        $ordersData = array();

        foreach ($servers as $server)
        {
            $serverOrders = self::fetchOrdersDataFromServer($server, $from, $to, $id);
            self::addMageSource($serverOrders, $server->name, $server->base_url, $server->ident);

            foreach ($serverOrders as $order)
            {
                $mageId = $order['increment_id'];

                $cached_order = MagentoCachedOrder::find_by_magento_id_and_server_id($mageId, $server->id);

                if (empty($cached_order))
                {
                    $cached_order = new MagentoCachedOrder();
                    $cached_order->operation_id = $operation->id;
                    $cached_order->magento_id = $mageId;
                    $cached_order->server_id = $server->id;
                    $cached_order->data = \Quantum\CompressedStorage::compress (serialize($order));
                    $cached_order->original_creation_date = $order['created_at'];
                    $cached_order->imported = 0;
                    $cached_order->save();

                    $cached_order->createProject($operation);
                }
            }

            $ordersData = array_merge($ordersData, $serverOrders);

        }


        $operation->end_date = datestamp();
        $operation->save();


        $operation->countImportedRecords();

        usort($ordersData, function($a, $b) {

            //dd($a);
            return $a['created_at'] < $b['created_at'];
        });


        return $ordersData;

    }

    public static function fetchOrderData($id, $server_id, $groups = null, $attribute_sets = null)
    {
        $cached_order = MagentoCachedOrder::find_by_magento_id_and_server_id($id, $server_id);

        if (empty($cached_order))
            return array();

        if (!empty($cached_order->full_mage_order))
            return unserialize(\Quantum\CompressedStorage::extract($cached_order->full_mage_order));


        $mage_server = $cached_order->server;
        $soap_url = $mage_server->getWSDLUrl();

        $client = new \SoapClient($soap_url, ['trace' => 1]);

        try {
            $session = $client->login($mage_server->api_user, $mage_server->api_key);
        } catch (\Exception $exception) {
            //dd($exception);
            \Quantum\Logger::custom('Error connecting to: '.$soap_url." - Error (OXAFF01):". $exception->getMessage(), 'mage_import_errors');
            //exit;
            return array();
        }

        if (!$groups)
        {
            $groups = \AppSettings::get($mage_server->ident.'_customer_groups');

            if (empty($groups))
            {
                $groups = $client->call($session, 'customer_group.list');
                \AppSettings::set($mage_server->ident.'_customer_groups', serialize($groups));
            }
            else
            {
                $groups = unserialize($groups);
            }
        }

        if (!$attribute_sets)
        {
            $attribute_sets = \AppSettings::get($mage_server->ident.'_attribute_sets');

            if (empty($attribute_sets))
            {
                $attribute_sets = $client->call($session, 'catalog_product_attribute_set.list');
                \AppSettings::set($mage_server->ident.'_attribute_sets', serialize($attribute_sets));
            }
            else
            {
                $attribute_sets = unserialize($attribute_sets);
            }
        }

        if (empty($cached_order->full_data))
        {
            $result = $client->call($session, 'sales_order.info', $id);
            $cached_order->full_data = \Quantum\CompressedStorage::compress(serialize($result));
            $cached_order->save();
        }
        else
        {
            $result = unserialize(\Quantum\CompressedStorage::extract($cached_order->full_data));
        }

        $mOrder = new MagentoOrder($client, $session, $result, $groups, $attribute_sets);


        $cached_order->full_mage_order = \Quantum\CompressedStorage::compress(serialize($mOrder));
        $cached_order->save();
        //echo json_encode($result, JSON_PRETTY_PRINT);
        //EXIT();
        //$address = $client->call($session, 'customer_address.info', $result['billing_address_id']);
        //$result['billing_address_info'] = $address;


        return $mOrder;



    }

    public static function fetchFullOrderDataFromMagento2Server($id, $server_id, $groups = null, $attribute_sets = null)
    {
        $cached_order = MagentoCachedOrder::find_by_magento_id_and_server_id($id, $server_id);

        if (empty($cached_order))
            return array();

        if (!empty($cached_order->full_mage_order))
            return unserialize(\Quantum\CompressedStorage::extract($cached_order->full_mage_order));

        $server = $cached_order->server;

        $token = self::getMagento2ApiToken($server);

        $sc = "searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=$id&searchCriteria[filter_groups][0][filters][0][condition_type]=eq`";

        $apiOrderUrl = $server->base_url.'index.php/rest/V1/orders/?'.$sc;

        //dd($apiOrderUrl);

        $ch = curl_init($apiOrderUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token));

        $result = curl_exec($ch);

        $results = json_decode($result, true);

        if (isset($results['items']))
        {
            $full_order_data = (object) $results['items'][0];

            if (empty($cached_order->full_data))
            {
                $result = $full_order_data;
                $cached_order->full_data = \Quantum\CompressedStorage::compress(serialize($full_order_data));
                $cached_order->save();
            }
            else
            {
                $result = unserialize(\Quantum\CompressedStorage::extract($cached_order->full_data));
            }

            //$mOrder = new MagentoOrder($client, $session, $result, $groups, $attribute_sets);


            $cached_order->full_mage_order = \Quantum\CompressedStorage::compress(serialize($full_order_data));
            $cached_order->save();

            return $full_order_data;
        }

        return [];

    }




    public static function addMageSource(&$array, $sourceName, $sourceUrl, $sourceIdent)
    {
        foreach ($array as &$result)
        {
            $result['mage_source'] = $sourceName;
            $result['mage_source_url'] = $sourceUrl;
            $result['mage_source_ident'] = $sourceIdent;

            $uri = '/orders/view_magento_order/'.$result['increment_id'].'?server='.$sourceIdent;
            $result['mage_admin_link'] = "<a href=$uri>".$result['increment_id']."</a>";
        }
    }

    public static function fetchOrdersDataFromServer(MagentoServer $server, $from, $to, $id = '')
    {
        if ($server->isV1())
        {
            return self::fetchOrdersDataFromMagento1Server($server, $from, $to, $id);
        }
        elseif ($server->isV2())
        {
            return self::fetchOrdersDataFromMagento2Server($server, $from, $to, $id);
        }
    }

    private static function getMagento2ApiToken(MagentoServer $server)
    {
        $userData = array("username" => $server->api_user, "password" => $server->api_key);
        $ch = curl_init($server->base_url."index.php/rest/V1/integration/admin/token");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));

        $token = curl_exec($ch);

        return json_decode($token);
    }

    private static function fetchOrdersDataFromMagento2Server($server, $from, $to, $id)
    {
        //dd($from);

        //https://stackoverflow.com/questions/19520523/magento-rest-filter-parametar-from
        $from = qs($from)->replace(' ', '%20')->toStdString();
        $to = qs($to)->replace(' ', '%20')->toStdString();

        $token = self::getMagento2ApiToken($server);

        $sc = "searchCriteria[filter_groups][0][filters][0][field]=created_at&searchCriteria[filter_groups][0][filters][0][condition_type]=from&searchCriteria[filter_groups][0][filters][0][value]=$from&searchCriteria[filter_groups][1][filters][0][field]=created_at&searchCriteria[filter_groups][1][filters][0][condition_type]=to&searchCriteria[filter_groups][1][filters][0][value]=$to";

        $apiOrderUrl = $server->base_url.'index.php/rest/V1/orders/?'.$sc;

        //dd($apiOrderUrl);

        $ch = curl_init($apiOrderUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token));

        $result = curl_exec($ch);
        $results = json_decode($result, true); // all orders with status pending

       // dd($results);

       if (isset($results['items']))
        {
            return $results['items'];
        }

       return [];

    }

    private static function fetchOrdersDataFromMagento1Server($server, $from, $to, $id)
    {
        $date_from = $from;
        $date_to = $to;

        //pr($date_from);
        //pr($date_to);
        //exit();

        $wsdl_url = $server->getWSDLUrl();
        $client = new \SoapClient($wsdl_url, ['trace' => 1]);

        //dd($server->getWSDLUrl());

        try {
            $session = $client->login($server->api_user, $server->api_key);
        } catch (\Exception $exception) {
            //dd($exception->getMessage());

            \Quantum\Logger::custom('Error connecting to: '.$wsdl_url." - Error:(OXAFF02)". $exception->getMessage(), 'mage_import_errors');
            \Quantum\Logger::custom(serialize($exception), 'mage_import_exceptions');
            //exit;
            return array();
        }

        if (!empty($id))
        {
            $data['increment_id'] = [ [ 'in' => [$id] ] ];
        }
        else
        {
            if (!empty($from) && !empty($to))
            {
                $data['created_at'] = array(
                    'from' => $date_from,
                    'to' => $date_to
                );
            }
        }

        $params = array($data);

        try {
            $result = $client->call($session, 'sales_order.list', $params);
        } catch (\Exception $exception) {
            \Quantum\Logger::custom('Error connecting to sales_order.list: '.$wsdl_url." - Error:(OXAFF03)". $exception->getMessage(), 'mage_import_errors');
            //exit;
            return array();
        }



        foreach ($result as &$order)
        {
            if (isset($order['store_name']))
            {
                if (qs($order['store_name'])->contains("\n"))
                {
                    $a = qs($order['store_name'])->explode("\n");
                    if (!empty($a))
                    {
                        $order['store_name'] = $a[0];
                    }
                }
            }
        }

        return $result;
    }

    public static function timestampToDateFrom($value = null)
    {
        $date = new \DateTime();

        return $date->format('Y-m-d 00-00-00');
    }

    public static function timestampToDateTo($value = null)
    {
        $date = new \DateTime();

        return $date->format('Y-m-d 23-59-59');
    }


}