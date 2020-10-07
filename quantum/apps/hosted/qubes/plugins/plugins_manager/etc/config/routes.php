<?php

return [
    array(
         'uri' => '/plugins',
        'controller' => 'Qubes\PluginsManager\PluginsController',
        'method' => 'index',
        'templates' => 'registered_access|normal_rate|csrf_enabled'
    ),
];
