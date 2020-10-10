<?php
/**
 * AccessLevel
*/
class TransactionalEmailRecipient extends ActiveRecord\Model
{

  	static $table_name = 'transactional_emails_recipients';

  	public static function findOrCreate($address, $name)
    {
        $recipient = TransactionalEmailRecipient::find_by_address_and_name($address, $name);

        if (empty($recipient))
        {
            $recipient = new TransactionalEmailRecipient();
            $recipient->address = $address;
            $recipient->name = $name;
            $recipient->domain = qs($address)->fromFirstOccurrenceOf('@');
            $recipient->save();
        }

        return $recipient;
    }
     

}

?>