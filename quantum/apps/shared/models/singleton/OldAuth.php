<?php

/**
 * Class Auth
 */
class Auth extends Quantum\Singleton
{
    /**
     * @var
     */
    public $user;

    /**
     * Auth constructor.
     */
    function __construct()
    {

    }

    /**
     * @param $username
     * @return bool|\User
     * @throws \ActiveRecord\ActiveRecordException
     */
    public static function getUserByUsername($username)
    {
        $user = User::find_by_username($username);

        if (!empty($user))
            return $user;

        return false;
    }

    /**
     * @param $code
     * @return bool|\User
     * @throws \ActiveRecord\ActiveRecordException
     */
    public static function getUserByEmployeeCode($code)
    {
        $user = User::find_by_quick_login_code($code);

        if (!empty($user))
            return $user;

        return false;
    }

    /**
     * @return bool|\User
     * @throws \ActiveRecord\ActiveRecordException
     */
    public static function getUserFromSession()
    {
        Quantum\Session::start();

        if (Quantum\Session::hasParam("user_id") && Quantum\Session::hasParam('user_hash'))
        {
            $user = User::find_by_id_and_hash(Quantum\Session::get('user_id'), Quantum\Session::get('user_hash'));

            if (!empty($user))
                return $user;
        }

        return false;

    }

    /**
     * @return bool|\User
     * @throws \ActiveRecord\ActiveRecordException
     */
    public static function getUserFromRememberMeCookie()
    {
        if (QM::request()->cookies()->has("RememberMeToken"))
        {
            $token = QM::request()->cookies()->get("RememberMeToken");
            $token = Quantum\Crypto::decryptWithLocalKey($token);

            $user = User::find_by_auto_login_token($token);

            if (!empty($user))
                return $user;
        }

        return false;
    }

    /**
     * @param $auth_client_key_string
     * @return bool|\User
     * @throws \ActiveRecord\ActiveRecordException
     * @throws \Quantum\InvalidHashException
     */
    public static function getUserFromExternalAuthToken($auth_client_key_string)
    {
        if (QM::request()->isPost() &&
            QM::request()->hasPostParam("auth_client_token") &&
            QM::request()->hasPostParam("auth_signature")&&
            QM::request()->hasPostParam("state"))
        {
            $token = QM::request()->getPostParam('auth_client_token');
            $token = Quantum\RSACrypto::base64Decrypt($token, $auth_client_key_string);
            $token = AuthUserToken::find_by_token($token);

            if (empty($token))
            {
                Quantum\ApiException::custom("Error 0x00AUTH01", "Invalid token", "Token not found");
            }

            $signature = QM::request()->getPostParam('auth_signature');
            $signature = Quantum\RSACrypto::base64Decrypt($signature, $auth_client_key_string);

            if (!Quantum\PasswordStorage::verify_password($signature, $token->signature))
            {
                Quantum\ApiException::custom("Error 0x00AUTH02", "Invalid token signature", "Invalid token signature");
            }

            if (Quantum\Session::hasParam("XAuthStateToken") && QM::request()->hasPostParam("state"))
            {
                $state = QM::request()->getPostParam('state');
                $state = Quantum\RSACrypto::base64Decrypt($state, $auth_client_key_string);

                $saved_state = Quantum\Session::get("XAuthStateToken");

                if (!hash_equals($saved_state, $state))
                    Quantum\ApiException::custom("Error 0x00AUTH03", "Invalid token state", "Invalid token state");

            }

            $user = User::find_by_id($token->user_id);

            if (!empty($user))
            {
                $token->refresh();
                $token->refreshSignature();
                return $user;
            }
        }

        return false;
    }


    /**
     * @param $user
     * @param $password
     * @return bool
     * @throws \Quantum\InvalidHashException
     */
    public static function isPasswordCorrect($user, $password)
    {
        return Quantum\PasswordStorage::verify_password($password, $user->password);
    }

    /**
     * @param $levelToSet
     * @throws \ActiveRecord\ActiveRecordException
     */
    public static function setAccessLevel($levelToSet)
    {
        if ($levelToSet == "user")
        {
            $user = self::getUserFromSession();

            if (empty($user))
                self::logout(self::getLoginUri());

            self::getInstance()->setUser($user);
        }
    }

    /**
     * @return string
     */
    public static function getLoginUri()
    {
        return "/auth/login";
    }

    /**
     * @param null $uri
     */
    public static function logout($uri = null)
    {




        /*

        $user = self::getUserFromSession();

        if ($user != false)
            $user->deleteRememberMeToken();

        if (Quantum\Cookies::getInstance()->has("auto_login_token"))
        {
            Quantum\Cookies::getInstance()->markForDeletion("auto_login_token");
        }

        */


        Quantum\Session::destroy();

        if ($uri != null)
            redirect_to($uri);
    }

    /**
     * @param $user
     */
    public static function initUserSession($user)
    {
        Quantum\Session::set('user_id', $user->id);
        Quantum\Session::set('user_hash', $user->hash);
        Quantum\Session::set('login_attempts', 0);
        Quantum\Session::set('recaptcha_enabled', 0);
        //Quantum\Session::set('QUANTUM_ENVIRONMENT', \QM::environment());

        $user->updateLastLogin();

        self::getInstance()->setUser($user);
    }

    /**
     * @param $user
     */
    public static function setUser($user)
    {
       self::getInstance()->user = $user;
    }

    /**
     * @return mixed
     */
    public static function  getUser()
    {
        return self::getInstance()->user;
    }

    /**
     * @return bool
     */
    public static function isRecaptchaValid()
    {
        $recaptcha = new \ReCaptcha\ReCaptcha("6LcWAYEUAAAAAAwdL12Ky9EOwG0gGFW9c9hxaNC7");

        $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
            ->verify(QM::request()->getPostParam('g-recaptcha-response'), $_SERVER['REMOTE_ADDR']);

        return $resp->isSuccess();
    }

    /**
     * @return bool
     * @throws \ActiveRecord\ActiveRecordException
     */
    public static function isUserSessionOpen()
    {
        $user = self::getUserFromSession();

        return !empty($user);
    }

    /**
     * @param $user
     */
    public static function createRememberMeCookie($user)
    {
        $login_token = $user->createRememberMeToken();
        $login_token = Quantum\Crypto::encryptWithLocalKey($login_token);

        QM::cookies()->set('RememberMeToken', $login_token, strtotime("+30 Days"), "/");
    }





}


?>