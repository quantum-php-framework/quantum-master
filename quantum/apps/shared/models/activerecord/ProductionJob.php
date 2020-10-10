<?php
/**
 * AccessLevel
*/
class ProductionJob extends ActiveRecord\Model {

  	static $table_name = 'production_jobs';

    static $belongs_to = array(
        array('creator', 'class_name' => 'User', 'foreign_key' => 'creator_user_id'),
        array('account', 'class_name' => 'Account', 'foreign_key' => 'account_id'),
        array('priority', 'class_name' => 'ProductionJobPriority', 'foreign_key' => 'priority_id'),
        array('type', 'class_name' => 'PriorityJobType', 'foreign_key' => 'type_id'),
        array('status', 'class_name' => 'PriorityJobStatus', 'foreign_key' => 'status_id')
    );



        
     

}

?>