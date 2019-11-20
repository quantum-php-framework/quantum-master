<?

/**
 * AuthUserToken
*/
class AuthUserToken extends ActiveRecord\Model { 

  	static $table_name = 'auth_user_tokens';

    static $belongs_to = array(
        array('user', 'class_name' => 'User', 'foreign_key' => 'user_id'),
        array('client', 'class_name' => 'AuthClient', 'foreign_key' => 'client_id')
    );


    function refresh()
    {
        $this->token = Quantum\CSRF::create(64);
        $this->save();

        return $this;
    }


    function refreshSignature()
    {
        $signature = Quantum\CSRF::create(64);
        $this->signature = Quantum\PasswordStorage::create_hash($signature);
        $this->save();

        return $signature;
    }
    
        
        
     

}

?>