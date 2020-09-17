<?php

return [
    'normal_rate' => [
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600'
    ],

    'csrf_disabled' => [
        'csrf_check_enabled' => '0'
    ],

    'csrf_enabled' => [
        'csrf_check_enabled' => '1'
    ],

    'admin_access' => [
        'min_access_level' => 'admin'
    ],

    'public_access' => [
        'min_access_level' => 'public'
    ],

    'registered_access' => [
        'min_access_level' => 'registered',
    ],

    'get' => [
        'http_request_methods' => 'GET'
    ],

    'post' => [
        'http_request_methods' => 'POST'
    ],

    'get_and_post' => [
        'http_request_methods' => 'GET|POST'
    ],

    'cache_enabled' => [
        'page_cache' => '1',
    ],

    'cache_disabled' => [
        'page_cache' => '0',
    ],

    'cache_everything' => [
        'page_cache_rule' => 'cache_everything'
    ],

    'ignore_query_string' => [
        'page_cache_rule' => 'ignore_query_string'
    ],

    'cache_logged_in_users' => [
        'page_cache_logged_in_users' => 1
    ],

];