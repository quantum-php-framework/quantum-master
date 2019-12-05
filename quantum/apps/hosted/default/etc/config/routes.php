<?php

$QUANTUM_APP_ROUTES = array
(

    array(
        'uri' => '/',
        'controller' => 'index',
        'method' => 'index',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET'
    ),

    array(
        'uri' => '/test',
        'controller' => 'index',
        'method' => 'test',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET'
    ),

    array(
        'uri' => '/method',
        'controller' => 'index',
        'method' => 'method',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'get|post'
    ),

    array(
        'uri' => '/test/some/route',
        'controller' => 'ExampleModule\Controllers\ExampleModule',
        'method' => 'index',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET'
    )
);




?>