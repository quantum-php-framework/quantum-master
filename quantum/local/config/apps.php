<?php

$QUANTUM_HOSTED_APPS = array(

    array(

        'uri' => 'quantum-dev',
        'dir' => 'default',
        'enabled' => true,
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'rate_limit' => '2000',
        'rate_limit_time' => '3600'

    ),

    array(

        'uri' => 'auth',
        'dir' => 'auth',
        'enabled' => true,
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600'
    ),

    array(

        'uri' => 'qubes',
        'dir' => 'qubes',
        'enabled' => true,
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600'
    ),


);