<?php

return [
    'version'  => '1',
    'prefix' => 'api/v',//mandatory if your version is an integer or a uuid
    'authorizations' => 'custom',// 'appkey,basic,userkey,custom',
    'autorization_middleware' => 'CustomMiddleware',
    'extra_data' => [
        'heading' => [
            'name' => '#ID',
            'heading-type' => 'General Information',
            'ngModel' => 'group_id',
            'enabled' => '0'
        ]
    ],
    'models' => array(

        array(
            'class_name'  => 'User',
            'plural_form' => 'users',
            'singular_form' => 'user',
            'features' => 'list,create,search,view,update,delete',
            'unique_attributes' => 'email',
            'id_attribute' => 'id',
            'order_attribute' => 'id',
            'default_order' => 'DESC',
            //'allowed_orders' => '',
            'default_limit' => 10,
            'max_limit' => 500,
            //'cache_ttl' => 300,
            'visible_attributes'  => [
                'id' => 'id',
                'name' => 'name',
                'lastname' => 'lastname',
                'full_name' => 'getFullName()',
                'email' => 'email'],
            'editable_attributes'  => [
                'name' => 'name',
                'lastname' => 'lastname',
                'email' => 'email',
            ],
            'searchable_attributes'  => [
                'name' => 'name',
                'email' => 'email',
            ],
            'create_validator_rules'=> [
                'name' => 'required|string',
                'lastname' => 'required|string'
            ],
            'update_validator_rules'=> [
                'name' => 'required|string',
                'lastname' => 'required|string'
            ],
            'extra_data' => [
                'player' => 'yes'
            ],
            'extra_routes' => [
                array(
                    'uri' => '/test',
                    'controller' => 'index',
                    'method' => 'test',
                    'http_request_methods' => 'POST',
                    'summary' => 'Test Custom Route',
                    'parameters' => [
                        array(
                            'name' => 'name',
                            'type' => 'string',
                            'format' => 'string',
                            'required' => 0,
                            'in' => 'formData'
                        )
                    ],
                    'validator_rules'=> [
                        'name' => 'required|string'
                    ],

                ),
            ]


        ),

    )

];
