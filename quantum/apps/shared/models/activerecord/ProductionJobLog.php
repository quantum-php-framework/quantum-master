<?

/**
 * AccessLevel
*/
class ProductionJobLog extends ActiveRecord\Model {

  	static $table_name = 'production_job_logs';

    static $belongs_to = array(
      array('job', 'class_name' => 'ProductionJob', 'foreign_key' => 'job_id')
    );



        
     

}

?>