<?php

use Quantum\ActiveAppFileDatabase;
use Quantum\Session;


class Auth extends Quantum\Singleton
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return bool|\User
     * @throws \ActiveRecord\ActiveRecordException
     */
    public static function getUserFromSession()
    {
        Session::start();

        if (Session::hasParam("username") )
        {
            $user_key = 'user_'.Session::get('username');

            $user = ActiveAppFileDatabase::get($user_key);


            //dd($user);
            if (!empty($user)) {
                return $user;
            }

            //qaq3de4   1Cdd($user);
        }

        return false;
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

            if (empty($user)) {
                //fuck();
                self::logout('/logout');
            }


            self::getInstance()->setUser($user);
        }
    }

    public static function logout($uri = null)
    {
        Quantum\Session::destroy();

        if ($uri != null)
            redirect_to($uri);
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


    public static function createDefaultAccessLevels()
    {
        $access_level = new Qubes\AccessLevel();
        $access_level->name = 'root';
        $access_level->priority = 99999;
        ActiveAppFileDatabase::set('access_level_root', $access_level);

        $access_level2 = new Qubes\AccessLevel();
        $access_level2->name = 'superadmin';
        $access_level2->priority = 9999;
        ActiveAppFileDatabase::set('access_level_superadmin', $access_level2);

        $access_level3 = new Qubes\AccessLevel();
        $access_level3->name = 'admin';
        $access_level3->priority = 999;
        ActiveAppFileDatabase::set('access_level_admin', $access_level3);

        $access_level4 = new Qubes\AccessLevel();
        $access_level4->name = 'registered';
        $access_level4->priority = 99;
        ActiveAppFileDatabase::set('access_level_registered', $access_level4);


    }


    public static function createDefaultUsers()
    {
        self::createDefaultAccessLevels();

        $acct = new Qubes\Account();
        $acct->name = 'default';
        $acct->uri = 'default';

        $user = new \Qubes\User();
        $user->username = 'admin';
        $user->name = 'admin';
        $user->access_level = 'root';
        $user->is_active = '1';
        $user->password = \Quantum\PasswordStorage::create_hash('Qubes123!');
        $user->account = $acct;

        return ActiveAppFileDatabase::set('user_admin', $user);
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

}


