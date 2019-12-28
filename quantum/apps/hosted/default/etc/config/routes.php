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
        'http_request_methods' => 'GET|POST',
        'http_cache_maxage' => '86400',
        'http_shared_cache_maxage' => '31536000',
    ),

    array(
        'uri' => '/some/options/route',
        'controller' => 'index',
        'method' => 'options',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        //'http_request_methods' => 'GET|POST|PUT|PATCH|DELETE|OPTIONS',
        'http_cache_maxage' => '86400',
        'http_shared_cache_maxage' => '31536000',

        'cors_enabled' => '1',
        'cors_allow_credentials' => '1',
        'cors_allowed_methods' => 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS',
        'cors_allowed_headers' => 'Content-Type, X-Auth-Token, Origin, Authorization, X-Requested-With, Accept',
        'cors_allowed_origins' => '*',
        'cors_expose_headers' => 'Cache-Control, Content-Language, Content-Type, Expires, Last-Modified, Pragma',
        'cors_denied_response_message' => 'Denied CORS Request',
        'cors_denied_response_status_code' => '403',
        'cors_max_age' => 86400,
        //'middlewares' => [SetRouteCacheHeader::class, CorsHandler::class]

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