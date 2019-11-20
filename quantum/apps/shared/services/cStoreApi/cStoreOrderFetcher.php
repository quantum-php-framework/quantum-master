<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 2/5/19
 * Time: 4:36 AM
 */

class cStoreOrderFetcher
{

    public static function fetchOrdersFromAllServers($from = '', $to = '', $id = '')
    {
        $servers = CstoreServer::all();

        $ordersData = array();

        foreach ($servers as $server)
        {
            if($server->isEnabled())
            {
                $serverOrders = self::fetchOrdersDataFromServer($server, $from, $to, $id);

                self::addServerSource($serverOrders, $server->name, $server->base_url, $server->ident);

                foreach ($serverOrders as $order)
                {
                    if (is_object($order))
                    {
                        $cstoreId = $order->id;

                        if (!empty($cstoreId))
                        {
                            $cached_order = CstoreCachedOrder::find_by_cstore_id_and_server_id($cstoreId, $server->id);

                            if (empty($cached_order))
                            {
                                $cached_order = new CstoreCachedOrder();
                                $cached_order->cstore_id = $cstoreId;
                                $cached_order->server_id = $server->id;
                                $cached_order->data = Quantum\CompressedStorage::compress (serialize($order));
                                $cached_order->original_creation_date = $order->created_at;
                                //$cached_order->full_data =
                                $cached_order->save();
                            }
                        }
                    }

                }

                $ordersData = array_merge($ordersData, $serverOrders);
            }

        }

        usort($ordersData, function($a, $b) {

            //dd($a);
            return $a->created_at < $b->created_at;
        });


        return $ordersData;

    }

    /*
    public static function fetchOrderData($id, $server_id, $groups = null, $attribute_sets = null)
    {
        $cached_order = CstoreCachedOrder::find_by_cstore_id_and_server_id($id, $server_id);

        if (empty($cached_order))
            return array();

        if (!empty($cached_order->full_cstore_order))
            return unserialize(Quantum\CompressedStorage::extract($cached_order->full_cstore_order));


        $cstore_server = $cached_order->server;
        $soap_url = $cstore_server->getOrdersByRangeUrl();

        $client = new \SoapClient($soap_url, ['trace' => 1]);

        try {
            $session = $client->login($cstore_server->api_user, $cstore_server->api_key);
        } catch (\Exception $exception) {
            dd($exception);
            Quantum\ApiException::custom('Error connecting to:'.$soap_url, '500', $exception->getMessage());
            exit;
        }

        if (!$groups)
        {
            $groups = AppSettings::get($cstore_server->ident.'_customer_groups');

            if (empty($groups))
            {
                $groups = $client->call($session, 'customer_group.list');
                AppSettings::set($cstore_server->ident.'_customer_groups', serialize($groups));
            }
            else
            {
                $groups = unserialize($groups);
            }
        }

        if (!$attribute_sets)
        {
            $attribute_sets = AppSettings::get($cstore_server->ident.'_attribute_sets');

            if (empty($attribute_sets))
            {
                $attribute_sets = $client->call($session, 'catalog_product_attribute_set.list');
                AppSettings::set($cstore_server->ident.'_attribute_sets', serialize($attribute_sets));
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


        $cached_order->full_cstore_order = Quantum\CompressedStorage::compress(serialize($mOrder));
        $cached_order->save();
        //echo json_encode($result, JSON_PRETTY_PRINT);
        //EXIT();
        //$address = $client->call($session, 'customer_address.info', $result['billing_address_id']);
        //$result['billing_address_info'] = $address;


        return $mOrder;



    }

    */


    public static function addServerSource(&$array, $sourceName, $sourceUrl, $sourceIdent)
    {
        foreach ($array as &$result)
        {
            $result->cstore_source = $sourceName;
            $result->cstore_source_url = $sourceUrl;
            $result->cstore_source_ident = $sourceIdent;

            $uri = '/orders/view_cstore_order/'.$result->id.'?server='.$sourceIdent;
            $result->cstore_admin_link = "<a href=$uri>".$result->id."</a>";
        }
    }

    public static function fetchOrdersDataFromServer($server, $from, $to, $id = '')
    {
        $api_url = $server->getOrdersByRangeUrl($from, $to);

        $request = new Quantum\CurlRequest($api_url);
        $request->setTimeout(640000);
        $request->setConnectTimeout(640000);
        $request->execute();

        if (!empty($request->getError()))
        {
            Quantum\Logger::custom('Error connecting to: '.$api_url." - Error:". $request->getError(), 'cstore_import_errors');
            //Quantum\ApiException::custom('Error connecting to:'.$api_url, '500', $request->getError());
        }


        $response = $request->getResponse();

        $result = array();

        if (qs($response)->isJson())
            $result = json_decode($response);
        else
            Quantum\Logger::custom('Error decoding orders json: '.$response, 'cstore_import_errors');

        return $result;
    }



}