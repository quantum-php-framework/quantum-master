<?php

namespace AutoRestApi;

use Quantum\ApiException;
use Quantum\Middleware\Foundation\SystemMiddleware;

class ValidateUserKeyAuth extends SystemMiddleware
{
    public function __construct()
    {

    }

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $key_param_name = get_active_app_setting('auto_rest_api_user_key_param_name', 'userkey');
        $key_attribute_name = get_active_app_setting('auto_rest_api_user_key_attribute_name', 'api_key');

        $request_key = $request->getParam($key_param_name, null);

        if (!$request_key) {
            $request_key = $request->getHeader($key_param_name);
        }

        if (!$request_key) {
            ApiException::custom('access_denied', '401 Unauthorized', 'Required '.$key_param_name);
        }

        $user = \User::find(array('conditions' => array("$key_attribute_name = ?", $request_key)));

        if (empty($user)) {
            ApiException::custom('access_denied', '401 Unauthorized', 'Wrong userkey');
        }
    }

}