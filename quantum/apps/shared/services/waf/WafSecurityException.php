<?

/**
 * AccessLevel
*/
class WafSecurityException extends ActiveRecord\Model {

  	static $table_name = 'waf_security_exceptions';

    /*static $belongs_to = array(
      array('user', 'class_name' => 'User', 'foreign_key' => 'user_id')
    ); */


    public static function getSecurityException($type, $ip, $sample = '')
    {
        $e = WafSecurityException::find_by_type_and_ip($type, $ip);
        $request = QM::request();

        if (empty($e))
        {
            $e = new WafSecurityException();
            $e->type = $type;
            $e->ip = $ip;
            $e->get_params = new_vt($request->getRawGet())->toJson();
            $e->post_params = new_vt($request->getRawPost())->toJson();
            $e->user_agent = $request->getBrowser()->getUserAgent();
            $e->country = $request->getVisitorCountry();
            $e->browser = $request->getBrowser()->getReadableName();
            $e->sample = $sample;
            $e->notified = 0;
            $e->recurrence_count = 0;
            $e->save();
        }

        return $e;
    }

    public function hasNotificationBeenSent()
    {
        return $this->notified === 1;
    }

    public function sendNotification()
    {
        \Quantum\Output::getInstance()->set('exception', $this);
        \Quantum\Output::getInstance()->set('request', QM::request());

        $html = Quantum\Output::getInstance()->fetchSystemMailView('wafexception');

        \Quantum\Mailer::notifyCreator('Club Hub WAF Security Exception',  $html);
        $this->notified = 1;
        $this->save();
    }

    public function notifyIfNeeded()
    {
        if (!$this->hasNotificationBeenSent())
            $this->sendNotification();
    }

    public function increaseRecurrenceCount()
    {
        $this->recurrence_count += 1;
        $this->save();
    }

    public static function logException($type, $sample = "")
    {
        $exception = WafSecurityException::getSecurityException($type, QM::request()->getIp(), $sample);

        $exception->notifyIfNeeded();

        $exception->increaseRecurrenceCount();
    }


        
     

}

?>