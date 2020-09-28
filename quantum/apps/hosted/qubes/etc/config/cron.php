<?php

return array
(
    array(
        'expression' => '*/3 * * * *',
        'class' => 'CronController',
        'method' => 'import_magento_orders',
        'allow_overlap' => '0',
        'enabled' => '1'
    ),

    array(
        'expression' => '*/3 * * * *',
        'class' => 'CronController',
        'method' => 'import_cstore_orders',
        'allow_overlap' => '0',
        'enabled' => '1'
    ),

    array(
        'expression' => '*/1 * * * *',
        'class' => 'CronController',
        'method' => 'send_transactional_emails',
        'allow_overlap' => '0',
        'enabled' => '1'
    ),

    array(
        'expression' => '*/4 * * * *',
        'class' => 'CronController',
        'method' => 'import_shopify_orders',
        'allow_overlap' => '0',
        'enabled' => '1'
    ),

    array(
        'expression' => '*/10 * * * *',
        'class' => 'CronController',
        'method' => 'push_shopify_orders',
        'allow_overlap' => '0',
        'enabled' => '1'
    ),


    array(
        'expression' => '*/2 * * * *',
        'class' => 'CronController',
        'method' => 'send_statuscake_heartbeat',
        'allow_overlap' => '0',
        'enabled' => '1'
    ),

);