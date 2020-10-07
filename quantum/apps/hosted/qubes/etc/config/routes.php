<?php

$QUANTUM_APP_ROUTES = array
(
    array(

        'uri' => '/',
        'controller' => 'index',
        'method' => 'index',
        'templates' => 'registered_access|normal_rate'

    ),

    array(

        'uri' => '/login',
        'controller' => 'login',
        'method' => 'index',
        'templates' => 'public_access|normal_rate|csrf_enabled'
    ),

    array(

        'uri' => '/logout',
        'controller' => 'login',
        'method' => 'logout',
        'templates' => 'public_access|normal_rate|csrf_enabled'
    ),





);




?>