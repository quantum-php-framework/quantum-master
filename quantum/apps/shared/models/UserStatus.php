<?

/**
 * UserStatus
*/
class UserStatus extends ActiveRecord\Model { 

  	static $table_name = 'user_statuses';

    /*static $belongs_to = array(
      array('user', 'class_name' => 'User', 'foreign_key' => 'user_id')
    ); */


    public static function getPublicStatusesAsKeyPair()
    {
        $statuses = new_vt(UserStatus::all());
        $data = new_vt();

        foreach ($statuses as $status)
        {
            if (qs($status->uri)->notEquals('deleted'))
                $data->set($status->id, $status->name);

        }

        return $data;
    }
    
        
        
     

}

?>