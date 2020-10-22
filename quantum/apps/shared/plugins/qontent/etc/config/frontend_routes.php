<?php

return [
    array(
        'uri' => '/qontent',
        'controller' => 'Qontent\Frontend\PostsController',
        'method' => 'post',
        'templates' => 'public_access|csrf_disabled',
        'page_cache' => 0
    ),
];
