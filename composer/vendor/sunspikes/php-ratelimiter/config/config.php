<?php
/**
 * Configure the adapter and cache drivers
 */

return [
    'default_ttl' => 3600,
    'driver'      => 'file',
    'notcache'    => [
        // config for not cache
    ],
    'file'        => [
        'cache_dir' => '../quantum/local/var/cache/ratelimit',
    ],
    'apc'         => [
        // config for apc
    ],
    'memory'      => [
       'limit' => 1000,
    ],
    'mongo'       => [
        'server' => 'mongodb://localhost:27017',
    ],
    'mysql'       => [
        'host'     => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'dbname'   => '',
        'port'     => 3306
    ],
    'redis'       => [
        // config for redis
    ],
    'memcache'    => [
        'servers' => [
            'localhost'
        ]
    ],
];
