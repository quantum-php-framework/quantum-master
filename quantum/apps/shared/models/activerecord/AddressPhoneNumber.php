<?php
/**
 * AccessLevel
*/
class AddressPhoneNumber extends ActiveRecord\Model {

  	static $table_name = 'address_phone_numbers';

    static $belongs_to = array(
        array('address', 'class_name' => 'StreetAddress', 'foreign_key' => 'address_id'),
        array('phone', 'class_name' => 'PhoneNumber', 'foreign_key' => 'phone_id')
    );



}

?>