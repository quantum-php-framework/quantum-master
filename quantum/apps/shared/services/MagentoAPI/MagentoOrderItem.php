<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 2/7/19
 * Time: 11:10 PM
 */

class MagentoOrderItem
{
    public function __construct(&$mageItem, $allItems, &$parent =  null)
    {

        foreach ($mageItem as $key => $value)
        {
            $this->$key = $value;
        }

        $this->parentItem = $parent;

        if (isset($this->product_options))
        {
            $this->product_options = unserialize($this->product_options);

            if (isset($this->product_options['bundle_selection_attributes']))
            {
                // {"price":0,"qty":5,"option_label":"MD","option_id":"56550"}
                $this->product_options['bundle_selection_attributes'] = unserialize($this->product_options['bundle_selection_attributes']);

            }
        }

        if (isset($this->product_options['attributes_info'][0]['value']))
        {
            $this->guessed_color = $this->product_options['attributes_info'][0]['value'];
        }
        elseif (isset($this->product_options['options']))
        {
            $options = $this->product_options['options'];

            foreach ($options as $option)
            {
                if (isset($option['label']) && $option['label'] == 'Color')
                    $this->guessed_color = $option['value'];
            }
        }

        if (!isset($this->guessed_color))
            $this->guessed_color = 'N/A';

        self::addToParentIfNeeded($this, $allItems);


    }

    public static function addToParentIfNeeded(&$parentItem, &$items)
    {
        foreach ($items as &$item)
        {
            if ($item['parent_item_id'] == $parentItem->item_id)
            {
                if (!isset($parentItem->real_children_items))
                {
                    $parentItem->real_children_items = array();
                }

                array_push($parentItem->real_children_items, new MagentoOrderItem($item, $items, $parentItem));
            }
        }
    }

    public function getQty()
    {
        if (isset($this->product_options['bundle_selection_attributes']))
        {
            return $this->product_options['bundle_selection_attributes']['qty'];
        }

        return "-1";
    }

    public function getUnitPrice()
    {
        if (isset($this->parentItem))
        {
            if (isset($this->parentItem->product_options['info_buyRequest']['bundle_size_option_price']) &&
                isset($this->product_options['bundle_selection_attributes']['option_id']))
            {

                $prices = $this->parentItem->product_options['info_buyRequest']['bundle_size_option_price'];

                foreach ($prices as $key => $price)
                {
                    if ($key == $this->product_options['bundle_selection_attributes']['option_id'])
                        return $price['unitPrice'];
                }
            }
        }

        return "-1";
    }

    public function hasLogo()
    {
        if (isset($this->product_options['bundle_options']))
        {
            $options = $this->product_options['bundle_options'];

            foreach ($options as $key => $option)
            {
                if (isset($option['label']) && $option['label'] == 'SELECT YOUR LOGO')
                    return true;
            }
        }

        return false;
    }

    public function getLogoName()
    {
        if (isset($this->product_options['bundle_options']))
        {
            $options = $this->product_options['bundle_options'];

            foreach ($options as $key => $option)
            {
                if (isset($option['label']) && $option['label'] == 'SELECT YOUR LOGO')
                    return qs($option['value'][0]['title'])->upToFirstOccurrenceOf("-")->trim();
            }
        }

        return false;
    }

    public function getLogoColor()
    {
        if (isset($this->product_options['bundle_options']))
        {
            $options = $this->product_options['bundle_options'];

            foreach ($options as $key => $option)
            {
                if (isset($option['label']) && $option['label'] == 'SELECT YOUR LOGO')
                    return qs($option['value'][0]['title'])->fromFirstOccurrenceOf("-")->trim();
            }
        }

        return false;
    }

    public function getLogoPosition()
    {
        if (isset($this->product_options['options']))
        {
            $options = $this->product_options['options'];

            foreach ($options as $option)
            {
                if (isset($option['label']) && $option['label'] == 'Logo Location')
                    return $option['value'];
            }
        }

        return "N/A";
    }

    public function getProductDataFromApi()
    {
        if (!isset($this->_productData))
        {
            $this->_productData = $this->_soapClient->call($this->_soapSession, 'catalog_product.info', $this->product_id);
            //dd($this->_productData);
        }
    }

    public function setSoapClient(&$client, &$session)
    {
        $this->_soapClient = $client;
        $this->_soapSession = $session;
        $this->getProductDataFromApi();
    }

    public function setAttributeSetsList($sets)
    {
        $this->_attributeSets = $sets;
    }

    public function getAttributeSetName()
    {
        $sku = $this->sku;

        if (qs($sku)->startsWith('ns-'))
            return "Pre-Designed";

        //
        $setId = $this->_productData['set'];

        foreach ($this->_attributeSets as $key => $attributeSet)
        {
            if ($attributeSet['set_id'] == $setId)
                return ($attributeSet['name']);
        }


        //
        return "N/A";

    }

    public function getTotalQty()
    {
        $total = 0;

        if (isset($this->product_options['info_buyRequest']['bundle_option_qty']))
        {
            $options = $this->product_options['info_buyRequest']['bundle_option_qty'];

            foreach ($options as $qty)
            {
                $total += $qty;
            }

            return $total;
        }


        if (isset($this->qty_ordered))
            $total = ceil($this->qty_ordered);

        return $total;
    }

    public function getTotalPrice()
    {
        $total = 0;

        if (isset($this->product_options['info_buyRequest']['bundle_size_option_price']))
        {
            $prices = $this->product_options['info_buyRequest']['bundle_size_option_price'];

            foreach ($prices as $price)
            {

                if (isset($price['totalPrice']))
                {
                    $total += $price['totalPrice'];
                }

            }


            return $total;
        }

        if (isset($this->price))
            $total = $this->price;

        return $total;
    }

    public function isVisibleInOrder()
    {
        if ($this->parent_item_id == '')
            return true;

        return false;
    }


}