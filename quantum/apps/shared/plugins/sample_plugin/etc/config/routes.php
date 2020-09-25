<?php

return [
    array(
        'uri' => '/wtf2',
        'controller' => 'SampleCompany\SamplePlugin\Controller',
        'method' => 'index2',
        'min_access_level' => 'public'
    ),

    array(
        'uri' => '/wtf',
        'controller' => 'SampleCompany\SamplePlugin\Controller',
        'method' => 'index',
        'templates' => 'public_access'
    ),
];
