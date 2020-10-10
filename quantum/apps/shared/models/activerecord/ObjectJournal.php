<?php
/**
 * AccessLevel
*/
class ObjectJournal extends ActiveRecord\Model {

  	static $table_name = 'core_object_journals';


    static $has_many = array(
        array('entries', 'class_name' => 'ObjectJournalEntry', 'foreign_key' => 'journal_id')
    );

    public function getEntries()
    {
        return $this->entries;
    }

    public function setParentKey($key)
    {
        $this->object_key = $key;
        $this->save();
    }

    public function setObjectClassName($className)
    {
        $this->object_class = $className;
        $this->save();
    }

    public function setObjectId($object_id)
    {
        $this->object_id = $object_id;
        $this->save();
    }

    public function setType($type)
    {
        $this->type = $type;
        $this->save();
    }

    public function info($message)
    {
        $this->addEntry($message, 'info', 'info');
    }

    public function error($message)
    {
        $this->addEntry($message, 'error', 'error');
    }

    public function success($message)
    {
        $this->addEntry($message, 'success', 'success');
    }

    public function addEntry($data, $type = 'info', $key = '')
    {
        $entry = new ObjectJournalEntry();
        $entry->journal_id = $this->id;
        $entry->data = $data;
        $entry->key = $key;
        $entry->type = $type;
        $entry->save();

        return $entry;
    }

    public function getEntriesTable()
    {
        $factory = new TableElementsFactory();

        $entries = $this->getEntries();

        $table = new Quantum\Table($factory);
        $table->addHeaders(['Date', 'Message']);
        $table->addModelData(array_reverse($entries), [
            'created_at|datetime_to_utc',
            'data'
        ]);

        return $table;
    }


}

?>