<?php
/**
 * WorkGroup
*/
class ProcurementOrderItem extends ActiveRecord\Model {

  	static $table_name = 'procurement_order_items';

    static $belongs_to = array(
        array('order', 'class_name' => 'ProcurementOrder', 'foreign_key' => 'order_id')
    );
    
        
     

}

?>