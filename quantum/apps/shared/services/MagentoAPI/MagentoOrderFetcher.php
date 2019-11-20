<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 2/5/19
 * Time: 4:36 AM
 */

class MagentoOrderFetcher
{

    public static function fetchOrdersFromAllServers($from = '', $to = '', $id = '')
    {
        $servers = MagentoServer::all();

        $ordersData = array();

        foreach ($servers as $server)
        {
            if($server->enabled == 1)
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
                        $cached_order->magento_id = $mageId;
                        $cached_order->server_id = $server->id;
                        $cached_order->data = Quantum\CompressedStorage::compress (serialize($order));
                        $cached_order->original_creation_date = $order['created_at'];
                        //$cached_order->full_data =
                        $cached_order->save();
                    }
                }

                $ordersData = array_merge($ordersData, $serverOrders);
            }

        }

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
            return unserialize(Quantum\CompressedStorage::extract($cached_order->full_mage_order));


        $mage_server = $cached_order->server;
        $soap_url = $mage_server->getWSDLUrl();

        $client = new \SoapClient($soap_url, ['trace' => 1]);

        try {
            $session = $client->login($mage_server->api_user, $mage_server->api_key);
        } catch (\Exception $exception) {
            //dd($exception);
            Quantum\Logger::custom('Error connecting to: '.$soap_url." - Error (OXAFF01):". $exception->getMessage(), 'mage_import_errors');
            //exit;
            return array();
        }

        if (!$groups)
        {
            $groups = AppSettings::get($mage_server->ident.'_customer_groups');

            if (empty($groups))
            {
                $groups = $client->call($session, 'customer_group.list');
                AppSettings::set($mage_server->ident.'_customer_groups', serialize($groups));
            }
            else
            {
                $groups = unserialize($groups);
            }
        }

        if (!$attribute_sets)
        {
            $attribute_sets = AppSettings::get($mage_server->ident.'_attribute_sets');

            if (empty($attribute_sets))
            {
                $attribute_sets = $client->call($session, 'catalog_product_attribute_set.list');
                AppSettings::set($mage_server->ident.'_attribute_sets', serialize($attribute_sets));
            }
            else
            {
                $attribute_sets = unserialize($attribute_sets);
            }
        }

        if (empty($cached_order->full_data))
        {
            $result = $client->call($session, 'sales_order.info', $id);
            $cached_order->full_data = Quantum\CompressedStorage::compress(serialize($result));
            $cached_order->save();
        }
        else
        {
            $result = unserialize(Quantum\CompressedStorage::extract($cached_order->full_data));
        }

        $mOrder = new MagentoOrder($client, $session, $result, $groups, $attribute_sets);


        $cached_order->full_mage_order = Quantum\CompressedStorage::compress(serialize($mOrder));
        $cached_order->save();
        //echo json_encode($result, JSON_PRETTY_PRINT);
        //EXIT();
        //$address = $client->call($session, 'customer_address.info', $result['billing_address_id']);
        //$result['billing_address_info'] = $address;


        return $mOrder;



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

    public static function fetchOrdersDataFromServer($server, $from, $to, $id = '')
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

            Quantum\Logger::custom('Error connecting to: '.$wsdl_url." - Error:(OXAFF02)". $exception->getMessage(), 'mage_import_errors');
            Quantum\Logger::custom(serialize($exception), 'mage_import_exceptions');
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
            Quantum\Logger::custom('Error connecting to sales_order.list: '.$wsdl_url." - Error:(OXAFF03)". $exception->getMessage(), 'mage_import_errors');
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