<?php
/**
 * AccessLevel
*/
class TransactionalEmailDeliveryAttempt extends ActiveRecord\Model {

  	static $table_name = 'transactional_emails_delivery_attempts';

    static $belongs_to = array(
        array('recipient', 'class_name' => 'TransactionalEmailRecipient', 'foreign_key' => 'recipient_id'),
        array('email', 'class_name' => 'TransactionalEmail', 'foreign_key' => 'transactional_email_id'),
        array('transport', 'class_name' => 'TransactionalEmailTransportServiceType', 'foreign_key' => 'transport_method_id')
    );

    public static function createAndAttempt($recipient_id, $email_id)
    {
        $mail = self::createFromRecipientAndEmailIds($recipient_id, $email_id);
        $mail->attempt();
    }

  	public static function createFromRecipientAndEmailIds($recipient_id, $email_id)
    {
        $attempt = new TransactionalEmailDeliveryAttempt();
        $attempt->recipient_id = $recipient_id;
        $attempt->transactional_email_id = $email_id;
        $attempt->transport_method_id = 1;
        $attempt->delivered = 0;
        $attempt->save();

        return $attempt;
    }

    public function attempt()
    {
        $subject = $this->email->subject;
        $html_contents = $this->email->content;
        $destination_name = $this->recipient->name;
        $destination_email = $this->recipient->address;

        $result = Quantum\Mailer::sendEmail($subject, $destination_email, $destination_name, $html_contents);

        if ($result > 0)
        {
            $this->delivered = 1;
        }
        else
        {
            $this->error = $result;
        }

        $this->save();
    }


}

?>