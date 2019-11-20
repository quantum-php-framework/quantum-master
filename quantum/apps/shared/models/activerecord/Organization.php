<?

/**
 * Organization
*/
class Organization extends ActiveRecord\Model {

  	static $table_name = 'organizations';

    /*static $belongs_to = array(
      array('user', 'class_name' => 'User', 'foreign_key' => 'user_id')
    ); */

    public function getAllAddresses()
    {
        $addresses = OrganizationAddress::find_all_by_org_id($this->id);

        return $addresses;
    }

    public function getAllPhoneNumbers()
    {
        $numbers = OrganizationPhoneNumber::find_all_by_org_id($this->id);

        return $numbers;
    }

    public function getAllContacts()
    {
        $contacts = Contact::find_all_by_org_id($this->id);

        return $contacts;
    }

    public function getContactsCount()
    {
        return count($this->getAllContacts());
    }

    public function getAddressesCount()
    {
        return count($this->getAllAddresses());
    }

    public function getPhoneNumbersCount()
    {
        return count($this->getAllPhoneNumbers());
    }

    public function getPrimaryContactName()
    {
        $contact = $this->getPrimaryContact();

        if ($contact)
            return $contact->name;

        return "N/A";

    }

    public function getPrimaryContactEmail()
    {
        $contact = $this->getPrimaryContact();

        if ($contact)
            return $contact->email;

        return "N/A";

    }

    public function getPrimaryContact()
    {
        if (!empty($this->primary_contact_id))
        {
            $contact = Contact::find_by_id($this->primary_contact_id);

            return $contact;
        }

        return null;
    }






    public function hasContacts()
    {
        return $this->getContactsCount() > 0;
    }

    public function hasAddresses()
    {
        return $this->getAddressesCount() > 0;
    }

    public function hasPhoneNumbers()
    {
        return $this->getPhoneNumbersCount() > 0;
    }



    public function getAllContactsTableHtml()
    {
        $contacts = $this->getAllContacts();

        return $this->getContactsTableHtmml($contacts);
    }

    public function getAllAddressesTableHtml()
    {
        $addresses = $this->getAllAddresses();

        return $this->getAddressesTableHtmml($addresses);
    }

    public function getAllPhoneNumbersTableHtml()
    {
        $numbers = $this->getAllPhoneNumbers();

        return $this->getPhoneNumbersTableHtmml($numbers);
    }


    public function getContactsTableHtmml($contacts)
    {
        $factory = new TableElementsFactory();
        $factory->setLink('/contact/', 'name');

        $table = new Quantum\Table($factory);
        $table->addHeaders([
            'Name',
            'Lastname',
            'Email'
        ]);
        $table->addModelData($contacts, [
            'name',
            'lastname',
            'email'
        ]);
        $table->addActionsColumn();

        //$table->setFactoryRowsMethod('getUserRowsHtml');
        //$table->setPages($pages);
        //$table->toOutput();

        return $table;
    }


    public function getAddressesTableHtmml($addresses)
    {
        $factory = new TableElementsFactory();
        $factory->setLink('/address/', 'getLabel()');

        $table = new Quantum\Table($factory);
        $table->addHeaders([
            'Label',
            'City',
            'State'
        ]);
        $table->addModelData($addresses, [
            'getLabel()',
            'getCity()',
            'getState()'
        ]);
        $table->addActionsColumn();

        //$table->setFactoryRowsMethod('getUserRowsHtml');
        //$table->setPages($pages);
        //$table->toOutput();

        return $table;
    }

    public function getPhoneNumbersTableHtmml($addresses)
    {
        $factory = new TableElementsFactory();
        $factory->setLink('/phone_number/', 'getLabel()');

        $table = new Quantum\Table($factory);
        $table->addHeaders([
            'Label',
            'Number',
            'Extension'
        ]);
        $table->addModelData($addresses, [
            'getLabel()',
            'getNumber()',
            'getExtension()'
        ]);
        $table->addActionsColumn();

        //$table->setFactoryRowsMethod('getUserRowsHtml');
        //$table->setPages($pages);
        //$table->toOutput();

        return $table;
    }


        
     

}