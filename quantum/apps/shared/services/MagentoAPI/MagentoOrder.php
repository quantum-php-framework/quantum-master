<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 2/7/19
 * Time: 11:10 PM
 */

class MagentoOrder
{
    public function __construct(&$client, &$session, &$result, &$customer_groups, &$attributeSets)
    {

        foreach ($result as $key => $value)
        {
            $this->$key = $value;
        }

        if (isset($this->quote_id))
        {
            $payments = $client->call($session, 'cart_payment.list', $this->quote_id);
        }


        //dd($payments);

        foreach ($customer_groups as $group)
        {
            if ($group['customer_group_id'] == $this->customer_group_id)
            {
                $this->customer_group_name = $group['customer_group_code'];
            }
        }


        if (isset($result['payment']))
        {
            foreach ($payments as $payment)
            {
                if ($payment['code'] == $this->payment['method'])
                {
                    $this->payment_method_name = $payment['title'];
                }
            }

            if (!isset($this->payment_method_name))
            {
                $this->payment_method_name = 'No Payment Information Required';
            }

            if (isset($this->payment['cc_type']))
            {
                $this->payment['cc_type'] = self::getCCType($this->payment['cc_type']);
            }
        }

        $this->mage_order_items = array();

        foreach ($this->items as &$item)
        {
            $mageItem = new MagentoOrderItem($item, $this->items);
            $mageItem->setSoapClient($client, $session);
            $mageItem->setAttributeSetsList($attributeSets);

            array_push($this->mage_order_items, $mageItem);
        }
    }

    public function getStatus()
    {
        if (isset($this->status))
        {
            return self::getStatusFromIdent($this->status);
        }
    }


    public static function getCCType($type)
    {
        switch ($type)
        {
            case 'VI': return "Visa"; break;
            case 'MC': return "Mastercard"; break;
            case 'AE': return "American Express"; break;
            case 'DI': return "Discovery"; break;
        }
    }

    public static function getStatusFromIdent($statusIdent)
    {
        switch ($statusIdent)
        {
            case 'mas_complete': return "MAS500 Complete"; break;
            case 'mas_processed': return "MAS500 Processed"; break;
            case 'pending': return "Pending"; break;
            case 'processing': return "Processing"; break;
        }

        return qs($statusIdent)->humanize();
    }





}