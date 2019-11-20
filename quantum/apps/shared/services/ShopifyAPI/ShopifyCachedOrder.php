<?

/**
 * AccessLevel
*/
class ShopifyCachedOrder extends Quantum\ActiverecordModel {

  	static $table_name = 'shopify_cached_orders';

    static $belongs_to = array(
      array('consumer', 'class_name' => 'ShopifyApiConsumer', 'foreign_key' => 'consumer_id')
    );


    public function getCXMLTimestamp()
    {
        return $this->created_at->format('rfc3339');
    }

    public function getJson()
    {
        return $this->getCached('json', json_decode($this->json_data));
    }

    public function getShippingAddress()
    {
        return $this->getJson()->shipping_address;
    }

    public function getBillingAddress()
    {
        return $this->getJson()->billing_address;
    }

    public function getItems()
    {
        return $this->getJson()->line_items;
    }



}

?>