<?php
/**
 * AccessLevel
*/
class ContactPhoneNumber extends ActiveRecord\Model {

  	static $table_name = 'contact_phone_numbers';

    static $belongs_to = array(
        array('contact', 'class_name' => 'Contact', 'foreign_key' => 'contact_id'),
        array('phone', 'class_name' => 'PhoneNumber', 'foreign_key' => 'phone_id')
    );



}

?>