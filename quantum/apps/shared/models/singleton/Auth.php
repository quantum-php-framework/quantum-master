<?php

use Quantum\Crypto;
use Quantum\Session;
use Quantum\RSACrypto;
use Quantum\ApiException;
use Quantum\PasswordStorage;
use Quantum\RuntimeRegistry;
use Quantum\Request;

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
        return from_runtime_registry('active_user_from_session', function() {

            Session::start();

            if (Session::hasParam("user_id") && Session::hasParam('user_hash'))
            {
                $user = User::find_by_id_and_hash(Session::get('user_id'), Session::get('user_hash'));

                if (!empty($user)) {
                    return $user;
                }
            }

            return false;

        });
    }

    /**
     * @return bool|\User
     * @throws \ActiveRecord\ActiveRecordException
     */
    public static function getUserFromRememberMeCookie()
    {
        $request = Request::getInstance();

        if ($request->cookies()->has("RememberMeToken"))
        {
            $token = $request->cookies()->get("RememberMeToken");
            $token = Crypto::decryptWithLocalKey($token);

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
        $request = Request::getInstance();

        if ($request->isPost() &&
            $request->hasPostParam("auth_client_token") &&
            $request->hasPostParam("auth_signature")&&
            $request->hasPostParam("state"))
        {
            $token = $request->getPostParam('auth_client_token');
            $token = RSACrypto::base64Decrypt($token, $auth_client_key_string);
            $token = AuthUserToken::find_by_token($token);

            if (empty($token)) {
                ApiException::custom("Error 0x00AUTH01", "Invalid token", "Token not found");
            }

            $signature = $request->getPostParam('auth_signature');
            $signature = RSACrypto::base64Decrypt($signature, $auth_client_key_string);

            if (!PasswordStorage::verify_password($signature, $token->signature)) {
                ApiException::custom("Error 0x00AUTH02", "Invalid token signature", "Invalid token signature");
            }

            if (Session::hasParam("XAuthStateToken") && $request->hasPostParam("state"))
            {
                $state = $request->getPostParam('state');
                $state = RSACrypto::base64Decrypt($state, $auth_client_key_string);

                $saved_state = Session::get("XAuthStateToken");

                if (!hash_equals($saved_state, $state)) {
                    ApiException::custom("Error 0x00AUTH03", "Invalid token state", "Invalid token state");
                }
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
        return PasswordStorage::verify_password($password, $user->password);
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
        RuntimeRegistry::remove('active_user_from_session');

        Session::destroy();

        if ($uri != null)
            redirect_to($uri);
    }

    /**
     * @param $user
     */
    public static function initUserSession($user)
    {
        Session::set('user_id', $user->id);
        Session::set('user_hash', $user->hash);
        Session::set('login_attempts', 0);
        Session::set('recaptcha_enabled', 0);
        //Session::set('QUANTUM_ENVIRONMENT', \QM::environment());

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
        $request = Request::getInstance();

        $recaptcha = new \ReCaptcha\ReCaptcha("6LcWAYEUAAAAAAwdL12Ky9EOwG0gGFW9c9hxaNC7");

        $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
            ->verify($request->getPostParam('g-recaptcha-response'), $_SERVER['REMOTE_ADDR']);

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
        $login_token = Crypto::encryptWithLocalKey($login_token);

        QM::cookies()->set('RememberMeToken', $login_token, strtotime("+30 Days"), "/");
    }

}