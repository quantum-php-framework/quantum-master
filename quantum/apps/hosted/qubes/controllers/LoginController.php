<?php
/*
 * class LoginController
 */

class LoginController extends Quantum\Controller
{
    var $user;

    function __construct()
    {
        Quantum\Session::start();
    }

    /**
     * Called after calling the main controller action, before calling Quantum\Output::render
     */
    protected function __pre_render()
    {
        $this->setRenderFullTemplate(false);
        //$this->setTemplate('marketplaces');
    }


    /**
     * Public: index
     */
    public function index() {

        $this->rememberMeCookieCheck();

        $this->hooks()->post('login_check_hook', ['username', 'password', "csrf"]);

        if (Quantum\Session::hasParam('admin_id'))
            redirect_to('/');

        if (Quantum\Session::isMissingParam('login_attempts'))
            Quantum\Session::set('login_attempts', 0);

        if (!empty($this->getData['redirect_uri'])) {
            $this->set('redirect_uri', $this->getData['redirect_uri']);
        }

    }

    public function logout($uri = null)
    {
        Auth::logout('/login');
    }


    private function rememberMeCookieCheck()
    {
        $user = Auth::getUserFromRememberMeCookie();

        if ($user != false) {
            $this->set("username", $user->username);
        }
    }

    private function isReCaptchaValid()
    {
        //return in_runtime_registry('recaptchav3_validated');
        return true;
    }

    private function login_check_hook()
    {
        if (!$this->isReCaptchaValid())
        {
            $this->loginFailed("Recaptcha Test Failed!");
            return;
        }

        $posted_username = $this->postData['username'];
        $posted_password = $this->postData['password'];

        if (empty($posted_username) || empty($posted_password))
        {
            $this->loginFailed('Empty username or password');
            return;
        }

        $user = Auth::getUserByUsername($posted_username);

        if ($user === false || !Auth::isPasswordCorrect($user, $posted_password))
        {
            $this->loginFailed();
            return;
        }

        if (!$user->isActive())
        {
            $this->loginFailed("Account disabled.");
            return;
        }

        $this->initUserSession($user);

    }

    private function loginFailed($error = "Sign in failed!")
    {
        $this->set('login_failed', 1);

        if (!empty($error))
            $this->set('error', $error);

        Quantum\Session::increaseCounter("login_attempts");
    }


    private function initUserSession($user)
    {
        Auth::initUserSession($user);

        if ($this->request->hasParam("remember_me"))
        {
            Auth::createRememberMeCookie($user);
        }
        else
        {
            if (QM::cookies()->has("RememberMeToken"))
                QM::cookies()->markForDeletion("RememberMeToken");
        }

        if($this->request->hasParam('redirect_uri'))
        {
            $redirect_url = base64_url_decode($this->request->getParam('redirect_uri'));
            redirect_to($redirect_url);
        }

        redirect_to('/');
    }


    ///pass recovery

    public function recover_password()
    {
        $this->renderFullTemplate = false;

        $this->passRecoveryHook();
        $this->set('title_for_layout', 'Marketplaces -- Recover Password');
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