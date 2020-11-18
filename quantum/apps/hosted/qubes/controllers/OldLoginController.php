<?php
/*
 * class LoginController
 */

class LoginController extends Quantum\Controller
{
    var $user;
    var $pubKey;
    var $privKey;


    function __construct()
    {
        Auth::createDefaultUsers();
        //exit();

        Quantum\Session::start();

        $this->pubKey = "6LdDAdwSAAAAANNF91dn5mAx7wRNruArsYcglwDt";
        $this->privKey = "6LdDAdwSAAAAANn4E3XD2hAJLiUbVUG_BnFRg7br";
    }


    /**
     * Public: index
     */
    public function index() {

        $this->hooks()->post('login_check_hook', ['username', 'password']);

        $this->renderFullTemplate = false;

        if (Quantum\Session::hasParam('admin_id'))
            redirect_to('/');

        if (Quantum\Session::isMissingParam('login_attempts'))
            Quantum\Session::set('login_attempts', 0);

        if (!empty($this->getData['wlf'])) {

            $this->set('wlf', $this->getData['wlf']);
        }

    }

    public function logout($uri = null)
    {
        Auth::logout('/login');
    }



    private function login_check_hook() {

        if (Quantum\Session::hasParam('max_login_attempts_reached'))
        {
            $this->set('error', "Too many failed attempts, please close your browser and try again.");
            return;
        }

        $posted_username = $this->request->getPostParam('username');

        $user_key = 'user_'.$posted_username;

        $user = \Quantum\ActiveAppKeyPairFileDb::get($user_key);

        if (!empty($user))
        {
            if (!Quantum\PasswordStorage::verify_password($this->postData['password'], $user->password))
            {
                $this->loginFailed();
                return;
            }

            Quantum\Session::set('admin_id', $user->username);
            Quantum\Session::set('username', $user->username);

            Quantum\Session::set('login_attempts', 0);
            Quantum\Session::set('using_recaptcha', 0);
            Quantum\Session::set('QUANTUM_ENVIRONMENT', $this->environment);

            if(!empty($this->postData['wlf']))
            {
                $wlf = Quantum\Utilities::base64_url_decode($this->postData['wlf']);
                redirect_to( $wlf);
            }
            else {
                redirect_to('/');
            }

        }
        else
        {
            $this->loginFailed();
        }

    }

    private function loginFailed()
    {
        $this->set('error', 'Invalid username or password');

        $_SESSION['login_attempts'] += 1;

        if ($_SESSION['login_attempts'] > 50)
        {
            $_SESSION['max_login_attempts_reached'] = 1;
        }
    }

    public function recover_password()
    {
        $this->renderFullTemplate = false;

        $this->passRecoveryHook();
        $this->set('title_for_layout', 'My NyrvSystems -- Recover Password');
    }

    public function change_password()
    {
        $this->renderFullTemplate = false;

        $this->user = Admin::find_by_hash($this->requestData['c']);

        $this->changePassHook();

        if (empty($this->user))
        {
            redirect_to('/login');
        }

        $this->set('user', $this->user);

        $this->set('title_for_layout', 'My NyrvSystems -- Change your password');


    }

    private function changePassHook()
    {
        if ( !empty($this->postData['password1']) && !empty($this->postData['password2']) )
        {

            if ($this->postData['password1'] != $this->postData['password2'])
            {
                $this->set('error', "Passwords don't match");
                return;
            }


            $user = CustomerAccountUser::find_by_hash($this->postData['c']);

            if (empty($user))
            {
                redirect_to('/login');
            }

            $password = to_password($this->postData['password1'], $this->environment->system_salt);

            $user->password = $password;
            $user->hash = Quantum\Utilities::genHash($user->email.$password.$user->hash);
            $user->save();
            $this->set('success', "Your password has been changed");

            $this->user = $user;

        }
    }


    private function passRecoveryHook() {

        if ( !empty($this->postData['username'])  ) {

            $user = CustomerAccountUser::find_by_email_and_account_activated($this->postData['username'], 1);

            if (empty($user))
            {
                $this->set('error', 'No user found for that email');
                return;
            }

            $this->set('user', $user);

            Quantum\Import::library('phpmailer/class.phpmailer.php');

            $html_contents  = $this->smarty->fetch($this->views_root."mails/recover_password_html.tpl");
            $text_contents  = $this->smarty->fetch($this->views_root."mails/recover_password_text.tpl");

            $mail             = new PHPMailer();
            $mail->IsSMTP();
            $mail->Host       = "smtp.mailgun.org"; // SMTP server
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->Host       = "smtp.mailgun.org"; // sets the SMTP server
            $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
            $mail->Username   = "team@nyrvsystems.com"; // SMTP account username
            $mail->Password   = "bKo8rDhXxKJ5-u6wthz4eQ";        // SMTP account password
            $mail->AddAddress($user->email, $user->name);
            $mail->SetFrom('team@nyrvsystems.com', 'NyrvSystems Team');
            $mail->AddReplyTo("team@nyrvsystems.com","NyrvSystems Team");
            $mail->Subject = 'Recover your password at clubcolors';
            $mail->AltBody = $text_contents; // optional - MsgHTML will create an alternate automatically
            $mail->MsgHTML($html_contents);

            if(!$mail->Send())
            {
                logger( "Mailer Error: " . $mail->ErrorInfo);
                $this->set('error', 'Problem sending your recovery pass email');

            }
            else
            {
                $this->set('success', 'Password recovery link has been sent to your email');
            }
        }

    }




}

?>