<?php

$QUANTUM_APP_ROUTES = array
(
    array(
        'uri' => '/login*',
        'controller' => 'login',
        'method' => 'index',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(
        'uri' => '/logout',
        'controller' => 'login',
        'method' => 'logout',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(
        'uri' => '/password/forgot',
        'controller' => 'password',
        'method' => 'index',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(
        'uri' => '/password/reset',
        'controller' => 'password',
        'method' => 'index',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(
        'uri' => '/tests*',
        'controller' => 'tests',
        'method' => 'index',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    )
);




?>