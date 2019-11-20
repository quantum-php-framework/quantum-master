<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 2/7/19
 * Time: 11:10 PM
 */

class ShopifyApiConsumer extends \Quantum\ActiverecordModel
{
    static $table_name = 'shopify_api_consumers';

    static $has_many = array(
        array('receivers', 'class_name' => 'ShopifyCxmlReceiver', 'foreign_key' => 'consumer_id')
    );

    public function getOrdersByRangeUrl($from, $to)
    {
        return $this->getBaseUrl().'api/orders_by_date_range?api_key='.$this->getApiKey().'&from='.urlencode($from).'&to='.urlencode($to);
    }

    public function getApiKey()
    {
        return $this->api_key;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getApiKeyPassword()
    {
        return $this->password;
    }

    public function isEnabled()
    {
        return $this->enabled === 1;
    }

    public function getBaseApiEndpoint()
    {
        $api_key = $this->getApiKey();
        $pwd = $this->getApiKeyPassword();

        $domain = $this->getUri().".myshopify.com";

        $url = "https://".$api_key.":".$pwd.'@'.$domain."/";

        return $url;
    }

    public function getOrdersApiEndpointUrl($from = "", $to = "")
    {
        $url = $this->getBaseApiEndpoint();

        $url .= 'admin/api/2019-10/orders.json';

        if (!empty($from) && !empty($to))
        {
            $url .= '?created_at_min='.$from."&created_at_max=".$to;
        }
        //dd($url);

        return $url;
    }

    public function notifyReceivers($cached_order)
    {
        $receivers = $this->receivers;

        foreach ($receivers as $receiver)
        {
            $receiver->notify($cached_order);
        }
    }
}