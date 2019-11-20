<?

/**
 * AccessLevel
*/
class TransactionalEmail extends ActiveRecord\Model
{
    static $table_name = 'transactional_emails';

    static function sendEmail($subject, $destination_email, $destination_name, $html_contents, $text_contents = "")
    {
        $recipient = TransactionalEmailRecipient::findOrCreate($destination_email, $destination_name);

        $transactional_email = self::findOrCreate($subject, $html_contents);

        TransactionalEmailDeliveryAttempt::createFromRecipientAndEmailIds($recipient->id, $transactional_email->id);
    }

    static function findOrCreate($subject, $html_contents)
    {
        $subject_hash = hash('sha1', $subject);
        $content_hash = hash('sha1', $html_contents);

        $transactional_email = TransactionalEmail::find_by_subject_hash_and_content_hash($subject_hash, $content_hash);

        if (empty($transactional_email))
        {
            $transactional_email = new TransactionalEmail();
            $transactional_email->subject = $subject;
            $transactional_email->content = $html_contents;
            $transactional_email->subject_hash = $subject_hash;
            $transactional_email->content_hash = $content_hash;
            $transactional_email->save();
        }

        return $transactional_email;
    }
        
     

}

?>