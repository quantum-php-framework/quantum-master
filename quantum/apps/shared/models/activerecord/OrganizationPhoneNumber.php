<?

/**
 * AccessLevel
*/
class OrganizationPhoneNumber extends ActiveRecord\Model {

  	static $table_name = 'organizations_phone_numbers';

    static $belongs_to = array(
        array('organization', 'class_name' => 'Organization', 'foreign_key' => 'org_id'),
        array('phone', 'class_name' => 'PhoneNumber', 'foreign_key' => 'phone_id')
    );

    public function getLabel()
    {
        return $this->phone->label;
    }

    public function getDescription()
    {
        return $this->address->description;
    }

    public function getNumber()
    {
        return $this->phone->number;
    }

    public function getExtension()
    {
        return $this->phone->extension;
    }


}

?>