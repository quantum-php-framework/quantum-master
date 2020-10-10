<?php
/**
 * AccessLevel
*/
class CstoreCachedOrder extends ActiveRecord\Model {

  	static $table_name = 'cstore_cached_orders';

    static $belongs_to = array(
      array('server', 'class_name' => 'CstoreServer', 'foreign_key' => 'server_id')
    );


    public function getSubtotal()
    {
        $t = round_cents($this->base_subtotal);
        return "$".$t;
    }


    public function restoreFullMageOrder()
    {
        if (empty($this->full_mage_order))
        {
            return MagentoOrderFetcher::fetchOrderData($this->magento_id, $this->server_id);
        }

        return unserialize(Quantum\CompressedStorage::extract($this->full_mage_order));
    }

    public function resetOrderCache()
    {
        $this->full_data = '';
        $this->full_mage_order = '';
        $this->save();
    }

    public function getData()
    {
        return unserialize(Quantum\CompressedStorage::extract($this->data));
    }

    public function getAdminLink()
    {
        $s = $this->server;

        return '/orders/view_cstore_order/'.$this->cstore_id.'?server='.$s->ident;
    }

}

?>