<?

/**
 * AccessLevel
*/
class PunchoutSession extends Quantum\ActiverecordModel {

  	static $table_name = 'punchout_sessions';

    static $belongs_to = array(
        array('user', 'class_name' => 'User', 'foreign_key' => 'user_id'),
        array('supplier', 'class_name' => 'ProcurementSupplier', 'foreign_key' => 'supplier_id')
    );

        
     

}

?>