<?php

namespace AutoRestApi;

use Quantum\ApiException;

class ValidateAppKeyAuth extends \Quantum\Middleware\Foundation\SystemMiddleware
{
    public function __construct()
    {

    }

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $keys = get_active_app_setting('auto_rest_api_app_keys', null);

        if (!$keys) {
            return;
        }

        $key_param_name = get_active_app_setting('auto_rest_api_app_key_param_name', 'appkey');

        $request_key = $request->getParam($key_param_name, null);

        if (!$request_key) {
            $request_key = $request->getHeader($key_param_name);
        }

        if (!$request_key) {
            ApiException::custom('access_denied', '401 Unauthorized', 'Required '.$key_param_name);
        }

        $keys = qs($keys)->explode(',');
        if (!in_array($request_key, $keys)) {
            ApiException::custom('access_denied', '401 Unauthorized', 'Invalid app_key');
        }
    }

}