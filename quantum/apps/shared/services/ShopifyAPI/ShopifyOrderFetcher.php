<?

class ShopifyOrderFetcher
{
    public static function fetchOrders($from = '', $to = '')
    {
        $fetched_orders = new_vt();

        $consumers = ShopifyApiConsumer::find_all_by_enabled(1);

        foreach ($consumers as $consumer)
        {
            $url = $consumer->getOrdersApiEndpointUrl($from, $to);

            $orders = json_decode(file_get_contents($url));

            if (empty($orders))
                break;

            $orders = $orders->orders;

            foreach ($orders as $order)
            {
                $cached_order = ShopifyCachedOrder::find_by_consumer_id_and_external_id($consumer->id, $order->id);

                if (empty($cached_order))
                {
                    $cached_order = new ShopifyCachedOrder();
                    $cached_order->consumer_id = $consumer->id;
                    $cached_order->external_id = $order->id;
                    $cached_order->email = $order->email;
                    $cached_order->external_creation_date = $order->created_at;
                    $cached_order->external_update_date = $order->updated_at;
                    $cached_order->number = $order->number;
                    $cached_order->token = $order->token;
                    $cached_order->gateway = $order->gateway;
                    $cached_order->total_price = $order->total_price;
                    $cached_order->subtotal_price = $order->subtotal_price;
                    $cached_order->total_weight = $order->total_weight;
                    $cached_order->total_tax = $order->total_tax;
                    $cached_order->name = $order->name;
                    $cached_order->total_price_usd = $order->total_price_usd;
                    $cached_order->browser_ip = $order->browser_ip;
                    $cached_order->total_line_items_price = $order->total_line_items_price;
                    $cached_order->currency = $order->currency;
                    $cached_order->financial_status = $order->financial_status;
                    $cached_order->phone = $order->phone;
                    $cached_order->json_data = json_encode($order);
                    $cached_order->xml_data = \Quantum\ExperimentalUtils::json2xml($cached_order->json_data);
                    $cached_order->save();

                    $fetched_orders->add($cached_order);

                    $consumer->notifyReceivers($cached_order);

                   // echo qs("Created order: ".$order->id)->withHtmlLineBreak();
                }


            }

        }

        return $fetched_orders;

    }

}


