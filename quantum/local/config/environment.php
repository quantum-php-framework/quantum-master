<?php

$QUANTUM_ENVIRONMENTS = array(

    //development
    array(
    	
		'domain' => 'quantum-dev.localhost',
        'db_host' => 'localhost',
        'db_name' => 'quantum_dev',
        'db_user' => 'root',
        'db_password' => 'root',
        'instance' => 'development',
        'set_controller_registry_variables_to_smarty' => '1',
        'system_salt' => 'S0H0vjKYnM8dQoZcQdH9',
        'environment_encryption_key' => 'def0000049f6fcb7a26397a04167099a246b46232dd06e2807decda6af82985323db50fb43200c5702de3124fb4412be112fcf811e1f9f58c3487cd08ed997fbee9d9746',
        'shared_encryption_key' => 'def00000e88974faf08b3bdfb555f35c3f7c0a2c45127b801d99fefc670c5d9333a60f50962109ec5dbf36f6e0f2293b88d7bf7f3827170984f744afd422522aafff8687',
        'redis_host' => '127.0.0.1',
        'redis_port' => '6379',
        'redis_password' => 'password',
        'redis_persistent' => '1',
        'redis_scheme' => 'tcp',
        'memcache_host' => '127.0.0.1',
        'memcache_port' => '11211',
        'cache_backend' => 'db'

    ),




);




?>