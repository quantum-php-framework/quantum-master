<?

/**
 * AccessLevel
*/
class ShopifyCxmlReceiver extends ActiveRecord\Model {

  	static $table_name = 'shopify_cxml_receivers';

    static $belongs_to = array(
        array('consumer', 'class_name' => 'ShopifyApiConsumer', 'foreign_key' => 'consumer_id'),
        array('type', 'class_name' => 'ShopifyCxmlReceiverType', 'foreign_key' => 'reciever_id')
    );

    public function isFtp()
    {
        return ($this->type_id === 1);
    }


    public function notify(ShopifyCachedOrder $order)
    {
        if (!$this->isEnabled())
            return;

        if ($this->isFtp())
            $this->createFtpTransfer($order);
    }

    public function isEnabled()
    {
        return $this->is_enabled === 1;
    }


    private function createFtpTransfer(ShopifyCachedOrder $order)
    {
        $factory = new CxmlFactory();

        $output = QM::output();
        $output->set('cxml_factory', $factory);
        $output->set('receiver', $this);
        $output->set('order', $order);
        $output->set('items', $order->getItems());
        $output->set('shipping_address', $order->getShippingAddress());
        $output->set('billing_address', $order->getBillingAddress());
        $output->set('json', $order->getJson());

        $cxml = $output->fetchFromTemplate('punchout', 'cxml/shopify_cxml_transfer.tpl');

        $output->unassign('cxml_factory');
        $output->unassign('receiver');
        $output->unassign('order');
        $output->unassign('items');
        $output->unassign('shipping_address');
        $output->unassign('billing_address');
        $output->unassign('json');

        $transfer = new ShopifyCxmlTransfer();
        $transfer->receiver_id = $this->id;
        $transfer->payload = $cxml;
        $transfer->order_id = $order->id;
        $transfer->delivered = 0;
        $transfer->in_process = 0;
        $transfer->save();

        $transfer->attempt();



    }

}

?>