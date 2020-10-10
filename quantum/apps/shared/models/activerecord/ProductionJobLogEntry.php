<?php
/**
 * AccessLevel
*/
class ProductionJobLogEntry extends ActiveRecord\Model {

  	static $table_name = 'production_job_log_entries';

    static $belongs_to = array(
      array('log', 'class_name' => 'ProductionJobLog', 'foreign_key' => 'log_id')
    );



        
     

}

?>