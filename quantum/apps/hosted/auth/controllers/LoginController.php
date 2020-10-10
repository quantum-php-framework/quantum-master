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
        Quantum\Session::start();

        $this->setTemplate('login');

        $this->pubKey = "6LcWAYEUAAAAAMtizG8F3Z65-gGW-jnyu_81QadD";
    }


    protected function __pre_dispatch()
    {
        $this->set('title_for_layout', 'Club Colors Login');

        $this->setRecaptchaIfNeeded();
    }


    public function index()
    {
        $this->initClient();

        $this->rememberMeCookieCheck();

        $this->hooks()->post ("loginCheckHook", array("username", "password", "csrf"));

        if ($this->request->hasParam("redirect_url"))
            $this->set('redirect_url', $this->request->getParam("redirect_url"));

        if ($this->request->hasParam("client_id"))
        {
            $client_id = get_request_param("client_id", "");
            $client_secret = get_request_param("client_secret", "");
            $state = get_request_param("state", "");
            $redirect_uri = get_request_param("redirect_uri", "");

            $codelogin_url = "/login/code?client_id=$client_id&client_secret=$client_secret&state=$state&redirect_uri=$redirect_uri";
            $this->set('codelogin_url', $codelogin_url);

            $regularlogin_url = "/login?client_id=$client_id&client_secret=$client_secret&state=$state&redirect_uri=$redirect_uri";
            $this->set('regularlogin_url', $regularlogin_url);

        }

    }


    public function logout()
    {
        Auth::logout("/login");
    }


    /**
     * Public: index
    */


    public function code()
    {
        $this->index();

        $this->hooks()->post ("loginWithUserCodeCheckHook", array("username", "csrf"));

        //$this->overrideMainView('login/code');

    }

    private function initClient()
    {
        if (Auth::isUserSessionOpen())
            self::logout();

        $this->client = $this->getClient();

        $this->set("client_id", $this->client->uri);
        $this->set("client", $this->client);
    }

    private function handlePossibleAccessByUri()
    {
        if (!$this->request->isMissingParams(array('client_secret', 'client_id')))
            return;

        if (!empty($this->requestUrl[2]))
        {
            $uri = $this->requestUrl[2];
        }

        if (!empty($this->requestUrl[3]))
        {
            $uri = $this->requestUrl[3];
        }

        if (!empty($uri))
        {
            $client = AuthClient::find_by_uri($uri);

            if (empty($client))
                Quantum\ApiException::custom("Invalid Auth Client", "Client not found", "Invalid Client URI");

            $this->request->redirect($client->getLoginRequestEndpoint());
        }
    }

    private function getClient()
    {
        $this->handlePossibleAccessByUri();

        if ($this->request->hasParam('client_id'))
        {
            $client_id = $this->request->getParam('client_id');
        }

        $client = AuthClient::find_by_client_id($client_id);

        if (empty($client))
            Quantum\ApiException::custom("Invalid Auth Client", "Client not found", "Invalid Client ID:");


        if ($this->request->hasParam('client_secret'))
        {
            $client_secret = $this->request->getParam('client_secret');

            $uncrypted_secret = Quantum\RSACrypto::base64Decrypt($client_secret, $client->getPrivateRSAKey());

            if (is_null($uncrypted_secret) || !hash_equals($client->getClientSecret(), $uncrypted_secret))
                Quantum\ApiException::accessDenied();
        }

        if ($this->request->hasParam('state'))
        {
            $request_state = $this->request->getParam('state');

            $uncrypted_state = Quantum\RSACrypto::base64Decrypt($request_state, $client->getPrivateRSAKey());

            Quantum\Session::set("XAuthStateToken", $uncrypted_state);
        }


        if ($this->request->hasParam('xcsrf'))
        {
            $request_csrf = $this->request->getParam('xcsrf');
            $request_csrf = Quantum\Utilities::base64_url_decode($request_csrf);

            Quantum\Session::set("xcsrf", $request_csrf);
        }

        if ($this->request->hasParam('redirect_uri'))
        {
            $redirect_uri = $this->request->getParam('redirect_uri');

            if (!$client->isReturnUrlValid($redirect_uri))
                Quantum\ApiException::custom("Invalid Auth Redirect Url", "Redirect Url is not valid", "Invalid Client redirect_uri");

            Quantum\Session::set("redirect_uri", $redirect_uri);
        }

        return $client;
    }


    private function rememberMeCookieCheck()
    {
        $user = Auth::getUserFromRememberMeCookie();

        if ($user != false)
        {
            $this->set("username", $user->username);
        }
    }

    private function isReCaptchaValid()
    {
        if (Quantum\Session::hasParam("recaptcha_enabled"))
        {
            return Auth::isRecaptchaValid();
        }

        return true;
    }

    private function loginCheckHook()
    {
        if (!$this->isReCaptchaValid())
        {
            $this->loginFailed("Check ReCaptcha Box");
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


        $this->initUserSession($user);

    }

    private function loginWithUserCodeCheckHook()
    {
        if (!$this->isReCaptchaValid())
        {
            $this->loginFailed("Check ReCaptcha Box");
            return;
        }

        $posted_username = $this->postData['username'];

        $user = Auth::getUserByEmployeeCode($posted_username);

        if (empty($user))
        {
            $this->loginFailed("Wrong Code");
        }
        else
        {
            $this->initUserSession($user);
        }

    }


    private function initUserSession($user)
    {
        if (!$user->isActive())
        {
            $this->loginFailed("Account disabled.");
            return;
        }

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

        $token = $user->getTokenForAuthClient($this->client->id);

        $token_code = Quantum\RSACrypto::base64Encrypt($token->token, $this->client->getPrivateRSAKey());

        $token_signature = Quantum\RSACrypto::base64Encrypt($token->refreshSignature(), $this->client->getPrivateRSAKey());

        $token_state = Quantum\RSACrypto::base64Encrypt(Quantum\Session::get("XAuthStateToken", Quantum\CSRF::create()), $this->client->getPrivateRSAKey());

        $token_csrf = Quantum\Session::get("xcsrf", Quantum\CSRF::create());

        $redirect_uri = Quantum\Session::get('redirect_uri');

        Quantum\Session::destroy();

        $url = $this->client->getBaseReturnUrl();
        $url = $url. "auth/login";

        $this->set('url', $url);
        $this->set('auth_client_token', $token_code);
        $this->set('auth_signature', $token_signature);
        $this->set('token_state', $token_state);
        $this->set('token_csrf', $token_csrf);
        $this->setIfNotEmpty('redirect_uri', $redirect_uri);
        $this->set('continue', true);
        $this->output->setMainView("login", "gateway");
    }
    
    private function loginFailed($error = "Sign in failed!")
    {
        $this->set('login_failed', 1);

        if (!empty($error))
            $this->set('error', $error);

        Quantum\Session::increaseCounter("login_attempts");

        if (Quantum\Session::get('login_attempts') > 2)
        {
            Quantum\Session::set("recaptcha_enabled", 1);
            $this->setRecaptchaIfNeeded();
        }
    }

    private function setRecaptchaIfNeeded()
    {
        if (Quantum\Session::hasParam("recaptcha_enabled"))
        {
            $this->set("recaptcha_pub_key", $this->pubKey);
        }
    }
    

    

        
        
     
    
}
