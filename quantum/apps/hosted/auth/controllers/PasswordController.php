<?

/*
 * class PasswordController
 */

class PasswordController extends Quantum\Controller
{
    
    /**
     * Create a controller, no dependency injection has happened.
    */
	function __construct()
	{

	}

    /**
     * Called after dependency injection, all environment variables are ready.
    */
	protected function __post_construct()
	{
        $this->setTemplate("login");
	}

    /**
     * Called before calling the main controller action, all environment variables are ready.
    */
	protected function __pre_dispatch()
	{
        $this->set('title_for_layout', 'Club Colors Password Reset');
	}

    /**
     * Called after calling the main controller action, all vars set by controller are ready.
    */
	protected function __post_dispatch()
	{

	}

	/**
     * Called after calling the main controller action, before calling Quantum\Output::render
    */
	protected function __pre_render()
	{

	}

	/**
     * Called after calling Quantum\Output::render
    */
	protected function __post_render()
	{

	}


	/**
     * Public: index
    */
    public function index()
    {
      
      
    }

    public function forgot()
    {
        $this->hooks()->post("sendRecoveryLinkHook", array("email", "csrf"));
    }

    public function reset()
    {
        if ($this->request->isMissingParams(array("token")))
            Quantum\ApiException::invalidParameters();

        $encrypted_token = $this->request->getParam("token");

        $decrypted_token = Quantum\Utilities::base64_url_decode($encrypted_token);
        $decrypted_token = Quantum\Crypto::decryptWithLocalKey($decrypted_token);

        $user = User::find_by_password_recovery_token($decrypted_token);
        QM::throwInvalidParametersIfEmpty($user);

        $this->user = $user;

        $this->set('token', $encrypted_token);
        $this->set('user',  $user);

        $this->hooks()->post("resetPassHook", array("password1", "password2", "csrf"));
    }

    private function resetPassHook()
    {
        if ($this->postData['password1'] != $this->postData['password2'])
        {
            $this->set('error', "Passwords don't match");
            return;
        }

        if (!Quantum\PasswordPolicy::isValid($this->postData['password1']))
        {
            $this->set('error', Quantum\PasswordPolicy::getPolicyDescription());
            return;
        }

        $encrypted_token = $this->request->getPostParam("token");

        $decrypted_token = Quantum\Utilities::base64_url_decode($encrypted_token);
        $decrypted_token = Quantum\Crypto::decryptWithLocalKey($decrypted_token);

        $user = User::find_by_password_recovery_token($decrypted_token);
        QM::throwInvalidParametersIfEmpty($user);

        $password = Quantum\PasswordStorage::create_hash($this->postData['password1']);

        $user->password = $password;
        $user->save();

        $user->refreshPassResetToken();

        $this->set('success', "Your password has been changed");

        $this->user = $user;
    }

    private function sendRecoveryLinkHook()
    {
        $user = User::find_by_email($this->postData['email']);

        if (empty($user))
        {
            $this->set('error', 'No user found for that email');
            return;
        }

        $this->set('user', $user);

        Quantum\Import::library('phpmailer/class.phpmailer.php');

        $pass_token = $user->getPassResetToken();
        $pass_token = Quantum\Crypto::encryptWithLocalKey($pass_token);
        $pass_token = Quantum\Utilities::base64_url_encode($pass_token);

        $url = $this->request->genFullURLFromURI("password/reset?token=".$pass_token);
        $this->set("reset_pass_url", $url);

        $view = $this->output->getMailViewInCurrentTemplate("recover_password_html.tpl");
        $html_contents  = $this->smarty->fetch($view);

        TransactionalEmail::sendEmail("Recover your password", $user->email, $user->getFullName(), $html_contents);

        $this->set('success', "Reset password email sent!");

    }
        
        
     
    
}

?>