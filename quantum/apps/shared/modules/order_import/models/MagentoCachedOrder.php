<?

namespace OrderImport;

class MagentoCachedOrder extends \Quantum\ActiverecordModel {

    static $table_name = 'oim_magento_cached_orders';

    static $belongs_to = array(
        array('server', 'class_name' => 'OrderImport\MagentoServer', 'foreign_key' => 'server_id')
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
            if ($this->server->isV1())
            {
                return MagentoImporter::fetchOrderData($this->magento_id, $this->server_id);
            }
            else
            {
                return MagentoImporter::fetchFullOrderDataFromMagento2Server($this->magento_id, $this->server_id);
            }

        }

        return unserialize(\Quantum\CompressedStorage::extract($this->full_mage_order));
    }

    public function resetFullMageOrderCache()
    {
        $this->full_data = '';
        $this->full_mage_order = '';
        $this->save();
    }

    public function getData()
    {
        return unserialize(\Quantum\CompressedStorage::extract($this->data));
    }

    public function getAdminLink()
    {
        $s = $this->server;

        return '/orders/view_magento_order/'.$this->magento_id.'?server='.$s->ident;
    }

    public function createProject($operation)
    {
        $factory = new ProjectFactory();

        $factory->processMagentoOrder($this, $operation);
    }

}

?>