<?php
/**
 * WorkGroup
*/
class ProcurementOrder extends ActiveRecord\Model {

  	static $table_name = 'procurement_orders';

    static $belongs_to = array(
        array('user', 'class_name' => 'User', 'foreign_key' => 'user_id'),
        array('account', 'class_name' => 'Account', 'foreign_key' => 'account_id'),
        array('supplier', 'class_name' => 'ProcurementSupplier', 'foreign_key' => 'supplier_id'),
        array('session', 'class_name' => 'PunchoutSession', 'foreign_key' => 'session_id')
    );

    static $has_many = array(
        array('items', 'class_name' => 'ProcurementOrderItem', 'foreign_key' => 'order_id')
    );

    public function getUserName()
    {
        return $this->user->getFullName();
    }

    public function getSupplierName()
    {
        return $this->supplier->label;
    }



}

?>