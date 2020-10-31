<?php

return [
    array(
        'class_name'  => 'User',
        'plural_form' => 'users',
        'features' => 'list,create,view,update,delete',
        'visible_attributes'  => [
            'id' => 'id',
            'name' => 'name',
            'lastname' => 'lastname',
            'full_name' => 'getFullName()',
            'email' => 'email'],
        'editable_attributes'  => [
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'email' => 'email',
            ],
        'searchable_attributes'  => [
            'name' => 'name',
            'email' => 'email',
        ],
    ),


];
