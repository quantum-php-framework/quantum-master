<?php

$QUANTUM_APP_ROUTES = array
(




    array(

        'uri' => '/',
        'controller' => 'index',
        'method' => 'index',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'registered',

    ),

    array(

        'uri' => '/apps',
        'controller' => 'apps',
        'method' => 'index',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'registered',
    ),

    array(

        'uri' => '/settings',
        'controller' => 'settings',
        'method' => 'index',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
        //'blocked_countries' => array ("CN", "localhost")
    ),

    array(

        'uri' => '/settings/procurement/suppliers',
        'controller' => 'ProcurementSettings',
        'method' => 'suppliers',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/procurement/suppliers/create',
        'controller' => 'ProcurementSettings',
        'method' => 'create_supplier',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/procurement/supplier/{id}',
        'controller' => 'ProcurementSettings',
        'method' => 'edit_supplier',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/users',
        'controller' => 'users',
        'method' => 'index',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/users/create',
        'controller' => 'users',
        'method' => 'create',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/user/edit/{id}',
        'controller' => 'users',
        'method' => 'edit',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/access_levels',
        'controller' => 'AccessLevels',
        'method' => 'index',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/access_levels/create',
        'controller' => 'AccessLevels',
        'method' => 'create',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/access_levels/edit/{id}',
        'controller' => 'AccessLevels',
        'method' => 'edit',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/timeclock/workgroups',
        'controller' => 'Timeclock',
        'method' => 'workgroups',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/timeclock/workgroup/create',
        'controller' => 'Timeclock',
        'method' => 'create_workgroup',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/timeclock/workgroup/{id}/create_workarea',
        'controller' => 'Timeclock',
        'method' => 'create_workarea',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/timeclock/workgroup/{id}/create_assignment',
        'controller' => 'Timeclock',
        'method' => 'create_workgroup_assignment',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/timeclock/workgroup/{id}/assignments',
        'controller' => 'Timeclock',
        'method' => 'view_workgroup_assignments',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/timeclock/workgroup/{id}',
        'controller' => 'Timeclock',
        'method' => 'view_workgroup',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/settings/timeclock/edit_workarea/{id}',
        'controller' => 'Timeclock',
        'method' => 'edit_workarea',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/timeclock/users',
        'controller' => 'Timeclock',
        'method' => 'users_browser',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/timeclock/user/{id}',
        'controller' => 'Timeclock',
        'method' => 'user',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),

    array(

        'uri' => '/timeclock/summary',
        'controller' => 'Timeclock',
        'method' => 'summary',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'admin',
    ),






    array(

        'uri' => '/orders/magento',
        'controller' => 'orders',
        'method' => 'magento',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/orders/cstore',
        'controller' => 'orders',
        'method' => 'cstore',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/orders/shopify',
        'controller' => 'orders',
        'method' => 'shopify',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/orders/view_magento_order/{id}',
        'controller' => 'orders',
        'method' => 'view_magento_order',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/orders/view_cstore_order/{id}',
        'controller' => 'orders',
        'method' => 'view_cstore_order',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/orders/view_shopify_order/{id}',
        'controller' => 'orders',
        'method' => 'view_shopify_order',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/slack',
        'controller' => 'slack',
        'method' => 'index',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/production_jobs/create_from_magento_order/{id}',
        'controller' => 'ProductionJobs',
        'method' => 'create_from_magento_order',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/procurement/punchout/{id}',
        'controller' => 'Procurement',
        'method' => 'punchout',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/procurement/order/{id}',
        'controller' => 'Procurement',
        'method' => 'order',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/procurement/suppliers',
        'controller' => 'Procurement',
        'method' => 'suppliers',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/procurement/orders',
        'controller' => 'Procurement',
        'method' => 'orders',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/procurement/punchin',
        'controller' => 'Procurement',
        'method' => 'punchin',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/procurement/start_punchout/{id}',
        'controller' => 'Procurement',
        'method' => 'start_punchout',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/procurement/cancel_punchout/{id}',
        'controller' => 'Procurement',
        'method' => 'cancel_punchout',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/auth/login',
        'controller' => 'auth',
        'method' => 'login',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
    ),

    array(

        'uri' => '/auth/logout',
        'controller' => 'auth',
        'method' => 'logout',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET|POST'
    ),

    array(

        'uri' => '/logout',
        'controller' => 'auth',
        'method' => 'logout',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/tools/fedexquoter',
        'controller' => 'Tools',
        'method' => 'fedex_quoter',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/organizations',
        'controller' => 'organizations',
        'method' => 'index',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/organizations/create',
        'controller' => 'organizations',
        'method' => 'create',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/organization/{id}',
        'controller' => 'organization',
        'method' => 'index',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => "GET"
    ),

    array(

        'uri' => '/organization/{id}/add_address',
        'controller' => 'organization',
        'method' => 'add_address',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => "GET"
    ),

    array(

        'uri' => '/organization/{id}/add_contact',
        'controller' => 'organization',
        'method' => 'add_contact',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => "GET"
    ),

    array(

        'uri' => '/organization/{id}/add_phone_number',
        'controller' => 'organization',
        'method' => 'add_phone_number',
        'min_access_level' => 'admin',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => "GET"
    ),

    array(

        'uri' => '/import_woocommerce_orders',
        'controller' => 'QMercium\Modules\WooCommerce\Controllers\WooCommerceModuleMainController',
        'method' => 'index',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET',
        //'blocked_countries' => array ("CN", "localhost")
    ),

    array(

        'uri' => '/tests/page_cache',
        'controller' => 'Tests',
        'method' => 'page_cache',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET',
        'min_access_level' => 'public',
        'page_cache' => '1',
        //'page_cache_rule' => 'cache_everything',
        'page_cache_expiration' => '30',
        'page_cache_logged_in_users' => 1
        //'blocked_countries' => array ("CN", "localhost")
    ),


    array(

        'uri' => '/tests/route_templates',
        'controller' => 'Tests',
        'method' => 'route_templates',
        'templates' => 'normal_rate|csrf_enabled|public_access|get_and_post|cache_enabled'
    ),

    /*
    array(

        'uri' => '/plugin',
        'controller' => 'ExampleModule\ExampleModule',
        'method' => 'index',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'registered',
    ),

    array(

        'uri' => '/plugin/anothermethod',
        'controller' => 'ExampleModule\ExampleModule',
        'method' => 'anothermethod',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'registered',
    ),

    array(

        'uri' => '/order_importer',
        'controller' => 'OrderImport\MagentoOrderImporterController',
        'method' => 'index',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'registered',
    ),

    array(

        'uri' => '/order_importer2',
        'controller' => 'OrderImport\MagentoOrderImporterController',
        'method' => 'index2',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET',
        'min_access_level' => 'registered',
    ),

    array(

        'uri' => '/import_shopify_orders',
        'controller' => 'Cron',
        'method' => 'import_shopify_orders',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET',
        'min_access_level' => 'public',
        //'blocked_countries' => array ("CN", "localhost")
    ),

    array(

        'uri' => '/send_statuscake_heartbeat',
        'controller' => 'Cron',
        'method' => 'send_statuscake_heartbeat',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET',
        'min_access_level' => 'public',
        //'blocked_countries' => array ("CN", "localhost")
    ),

    array(

        'uri' => '/import_woocommerce_orders',
        'controller' => 'QMercium\Modules\WooCommerce\Controllers\WooCommerceModuleMainController',
        'method' => 'index',
        'session_rate_limit' => '5600',
        'session_rate_limit_time' => '3600',
        'rate_limit' => '3000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET',
        'min_access_level' => 'public',
        //'blocked_countries' => array ("CN", "localhost")
    ),

    array(

        'uri' => '/cron*',
        'controller' => 'cron',
        'method' => 'index',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),

    */

    array(

        'uri' => '/api/monitor',
        'controller' => 'api',
        'method' => 'monitor',
        'min_access_level' => 'public',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET'
    ),

    array(

        'uri' => '/api*',
        'controller' => 'api',
        'method' => 'index',
        'min_access_level' => 'public',
        'rate_limit' => '1000000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '0',
        'http_request_methods' => 'GET'
    ),


    array(

        'uri' => '/tests*',
        'controller' => 'tests',
        'method' => 'index',
        'min_access_level' => 'public',
        'rate_limit' => '1000',
        'rate_limit_time' => '3600',
        'csrf_check_enabled' => '1',
        'http_request_methods' => 'GET'
    ),



);




?>