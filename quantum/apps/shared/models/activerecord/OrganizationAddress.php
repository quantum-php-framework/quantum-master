<?php
/**
 * AccessLevel
*/
class OrganizationAddress extends ActiveRecord\Model {

  	static $table_name = 'organizations_addresses';

    static $belongs_to = array(
        array('organization', 'class_name' => 'Organization', 'foreign_key' => 'org_id'),
        array('address', 'class_name' => 'StreetAddress', 'foreign_key' => 'address_id')
    );


    public function getAddress()
    {
        return $this->address;
    }

    public function getOrganization()
    {
        return $this->organization;
    }

    public function getLabel()
    {
        return $this->address->label;
    }

    public function getDescription()
    {
        return $this->address->description;
    }

    public function getLine1()
    {
        return $this->address->line1;
    }

    public function getLine2()
    {
        return $this->address->line2;
    }

    public function getLine3()
    {
        return $this->address->line3;
    }

    public function getCountry()
    {
        return $this->address->country;
    }

    public function getCity()
    {
        return $this->address->city;
    }

    public function getZip()
    {
        return $this->address->zip;
    }

    public function getNote()
    {
        return $this->address->note;
    }

    public function getState()
    {
        return $this->address->state;
    }

    public function destroy()
    {
        $address = $this->address;

        if (!empty($address))
            $address->delete();

        $this->delete();
    }

}

?>