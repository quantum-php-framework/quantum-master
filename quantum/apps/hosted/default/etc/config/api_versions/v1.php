<?php

return [
    'version'  => '1',
    'prefix' => 'api/v',//mandatory if your version is an integer or a uuid
    'authorizations' => 'custom', // 'appkey,basic,userkey,custom',
    'autorization_middleware' => 'CustomMiddleware',
    'models' => array(

        array(
            'class_name'  => 'User',
            'plural_form' => 'users',
            'singular_form' => 'user',
            'features' => 'list,create,search,view,update,delete',
            'unique_attributes' => 'email',
            'id_attribute' => 'id',
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
        ),




    )

];