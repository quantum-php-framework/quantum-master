<?php

namespace AutoRestApi;

use Quantum\ApiException;
use Quantum\PasswordStorage;
use Quantum\Middleware\Foundation\SystemMiddleware;

class ValidateBasicUserPwdAuth extends SystemMiddleware
{
    public function __construct()
    {

    }

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $header = $request->getHeader('authorization');

        if ((!$header)) {
            ApiException::custom('access_denied', '401 Unauthorized', 'Missing Authorization header');
        }

        $token = qs($header)->remove(' ')->fromFirstOccurrenceOf('Basic')->decodeBase64();

        if ($token->isEmpty() || !$token->contains(':')) {
            ApiException::custom('access_denied', '401 Unauthorized', 'Invalid Authorization header');
        }

        $user_data = $token->explode(':');

        if (!count($user_data) == 2) {
            ApiException::custom('access_denied', '401 Unauthorized', 'Wrong Authorization header');
        }

        $username_attr = get_active_app_setting('auto_rest_api_username_attribute', 'username');
        $password_attr = get_active_app_setting('auto_rest_api_password_attribute', 'password');

        $username = $user_data[0];
        $password = $user_data[1];

        $user = \User::find(array('conditions' => array("$username_attr = ?", $username)));

        if (empty($user)) {
            ApiException::custom('access_denied', '401 Unauthorized', 'Wrong username or password');
        }

        if (!PasswordStorage::verify_password($password, $user->$password_attr)) {
            ApiException::custom('access_denied', '401 Unauthorized', 'Wrong username or password');
        }

    }

}