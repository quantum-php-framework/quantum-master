<?php
/**
 * AccessLevel
*/
class ObjectJournalEntry extends ActiveRecord\Model {

  	static $table_name = 'core_object_journal_entries';


    static $belongs_to = array(
        array('journal', 'class_name' => 'ObjectJournal', 'foreign_key' => 'journal_id')
    );


}

?>