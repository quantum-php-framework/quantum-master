<?php

return [
    array(
        'uri' => '/qontent',
        'controller' => 'Qontent\Backend\PostsController',
        'method' => 'index',
        'min_access_level' => 'public'
    ),

    array(
        'uri' => '/qontent/new',
        'controller' => 'Qontent\Backend\PostsController',
        'method' => 'new_post',
        'min_access_level' => 'public'
    ),

    array(
        'uri' => '/qontent/settings',
        'controller' => 'Qontent\Backend\SettingsController',
        'method' => 'index',
        'min_access_level' => 'public'
    ),
];
