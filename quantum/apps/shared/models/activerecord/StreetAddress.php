<?php
/**
 * AccessLevel
*/
class StreetAddress extends ActiveRecord\Model {

  	static $table_name = 'street_addresses';

    static $belongs_to = array(
      array('type', 'class_name' => 'StreetAddressType', 'foreign_key' => 'type_id')
    );



}

?>