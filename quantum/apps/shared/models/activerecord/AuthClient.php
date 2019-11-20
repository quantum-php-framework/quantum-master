<?

/**
 * AuthClient
*/
class AuthClient extends ActiveRecord\Model { 

  	static $table_name = 'auth_clients';

    /*static $belongs_to = array(
      array('user', 'class_name' => 'User', 'foreign_key' => 'user_id')
    ); */

    public function getBaseReturnUrl()
    {
        $instance = QM::environment()->instance;

        switch ($instance)
        {
            case 'development':
                $redirect_uri = $this->dev_auth_redirect_url;
                break;
            case 'production':
                $redirect_uri = $this->production_auth_redirect_url;
                break;
        }

        return $redirect_uri;
    }

    public function getLoginRequestEndpoint()
    {
        return self::getBaseReturnUrl()."auth/login";
    }

    public function getAuthUrl()
    {

        $client_id     = $this->getClientId();

        $client_secret = Quantum\RSACrypto::base64Encrypt($this->getClientSecret(), $this->getPublicRSAKey());

        $state = Quantum\CSRF::create();
        QM::session()->set("XAuthStateToken", $state);
        $state = Quantum\RSACrypto::base64Encrypt($state, $this->getPublicRSAKey());

        $csrf = base64_url_encode(Quantum\Crypto::encryptWithLocalKey(\QM::session()->get('csrf')));

        $redirect_uri = qs(QM::request()->getPublicUrl())->remove('auth/login');

        $base = QM::request()->getDomainOnly();
        $url = QM::request()->getProtocol(true);
        $url .= "auth.".$base."/login?client_id=".$client_id."&client_secret=".$client_secret."&state=".$state."&xcsrf=".$csrf."&redirect_uri=".$redirect_uri;

        return $url;
    }

    public function getEncryptionKeyString()
    {
        if (empty($this->encryption_key))
        {
            $this->encryption_key = Quantum\SystemEncryptor::encrypt(Quantum\Crypto::genKey());
            $this->save();
        }

        return  Quantum\SystemEncryptor::decrypt($this->encryption_key);
    }

    public function getEncryptionKey()
    {
        return Quantum\Crypto::getKeyFromString($this->getEncryptionKeyString());
    }

    public function getClientSecret()
    {
        if (empty($this->client_secret))
        {
            $this->client_secret =  Quantum\SystemEncryptor::encrypt(Quantum\CSRF::create(64));
            $this->save();
        }

        return  Quantum\SystemEncryptor::decrypt($this->client_secret);
    }

    public function getClientId()
    {
        if (empty($this->client_id))
        {
            $client_id = substr(Quantum\CSRF::create(), 0, 24);
            $this->client_id =  $client_id;
            $this->save();
        }

        return ($this->client_id);
    }

    public function genRsaKeyPair()
    {
        $key = \Quantum\RSACrypto::genKey();
        $private_key = $key['privatekey'];
        $public_key  = $key['publickey'];

        $this->public_key  = \Quantum\SystemEncryptor::encrypt($public_key);
        $this->private_key = \Quantum\SystemEncryptor::encrypt($private_key);
        $this->save();
    }

    public function getPublicRSAKey()
    {
        if (empty($this->public_key))
            $this->genRsaKeyPair();

        return \Quantum\SystemEncryptor::decrypt($this->public_key);
    }

    public function getPrivateRSAKey()
    {
        if (empty($this->public_key))
            $this->genRsaKeyPair();

        return \Quantum\SystemEncryptor::decrypt($this->private_key);
    }

    public function getAuthSessionStartRedirectUrls()
    {
        $urls = [$this->dev_auth_redirect_url, $this->production_auth_redirect_url];

        return $urls;
    }

    public function isReturnUrlValid($url)
    {
        $url = qs($url);
        $valid_urls = $this->getAuthSessionStartRedirectUrls();

        foreach ($valid_urls as $valid_url)
        {
            if ($url->startsWith($valid_url))
                return true;
        }

        return false;
    }




    
        
        
     

}
