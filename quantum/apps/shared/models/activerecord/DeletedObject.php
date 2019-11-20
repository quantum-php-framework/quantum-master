<?

/**
 * AccessLevel
*/
class DeletedObject extends Quantum\ActiverecordModel {

  	static $table_name = 'deleted_objects';

    static $belongs_to = array(
        array('user', 'class_name' => 'User', 'foreign_key' => 'user_id'),
        array('account', 'class_name' => 'Account', 'foreign_key' => 'account_id')
    );

        
     

}

?>