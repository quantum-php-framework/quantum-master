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
            'name' => 'first_name',
            'lastname' => 'last_name',
            'email' => 'email',
            ],
        'searchable_attributes'  => [
            'name' => 'name',
            'email' => 'email',
        ],
    ),


];
