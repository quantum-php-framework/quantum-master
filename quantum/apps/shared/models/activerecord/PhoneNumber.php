<?

/**
 * AccessLevel
*/
class PhoneNumber extends ActiveRecord\Model {

  	static $table_name = 'phone_numbers';

    static $belongs_to = array(
      array('type', 'class_name' => 'PhoneNumberType', 'foreign_key' => 'type_id')
    );



}

?>