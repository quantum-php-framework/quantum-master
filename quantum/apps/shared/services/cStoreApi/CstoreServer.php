<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 2/7/19
 * Time: 11:10 PM
 */

class CstoreServer extends \Quantum\ActiverecordModel
{
    static $table_name = 'cstore_servers';


    public function getOrdersByRangeUrl($from, $to)
    {
        return $this->getBaseUrl().'api/orders_by_date_range?api_key='.$this->getApiKey().'&from='.urlencode($from).'&to='.urlencode($to);
    }

    public function getApiKey()
    {
        return $this->api_key;
    }

    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function isEnabled()
    {
        return $this->enabled === 1;
    }

}