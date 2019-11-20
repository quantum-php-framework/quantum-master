<?

/**
 * AccessLevel
*/
class Contact extends ActiveRecord\Model {

  	static $table_name = 'contacts';

    static $belongs_to = array(
        array('organization', 'class_name' => 'Organization', 'foreign_key' => 'org_id'),
        array('type', 'class_name' => 'ContactType', 'foreign_key' => 'type_id')
    );


    public function getAddresses()
    {
        $addresses = ContactAddress::find_all_by_contact_id($this->id);

        return $addresses;
    }

    public function getPhoneNumbers()
    {
        $numbers = ContactPhoneNumber::find_all_by_contact_id($this->id);

        return $numbers;
    }


}

?>