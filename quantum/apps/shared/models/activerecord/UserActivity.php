<?

/**
 * AccessLevel
*/
class UserActivity extends Quantum\ActiverecordModel {

  	static $table_name = 'user_activities';

    static $belongs_to = array(
        array('user', 'class_name' => 'User', 'foreign_key' => 'user_id')
    );

        
     

}

?>