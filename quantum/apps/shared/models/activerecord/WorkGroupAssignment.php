<?

/**
 * WorkGroupAssignment
*/
class WorkGroupAssignment extends ActiveRecord\Model { 

  	static $table_name = 'work_group_assignments';

    static $belongs_to = array(
        array('user', 'class_name' => 'User', 'foreign_key' => 'user_id'),
        array('group', 'class_name' => 'WorkGroup', 'foreign_key' => 'work_group_id')
    );

    public function getUserName()
    {
        return $this->user->getFullName();
    }


    
        
        
     

}

?>