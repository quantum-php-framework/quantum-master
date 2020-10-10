<?php
/**
 * AccessLevel
*/
class ContactAddress extends ActiveRecord\Model {

  	static $table_name = 'contact_addresses';

    static $belongs_to = array(
        array('contact', 'class_name' => 'Contact', 'foreign_key' => 'contact_id'),
        array('address', 'class_name' => 'StreetAddress', 'foreign_key' => 'address_id')
    );



}

?>