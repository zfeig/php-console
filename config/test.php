<?php 

$cfg = [
    'db' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => '192.168.250.1',
            'port' => 3306,
            'user' => 'root',
            'pwd' => 'root',
            'prefix' => '',
            'database' => 'istore2'
        ]
    ],
    'redis' => [
        'host' => '192.168.250.1',
        'port' => 6379,
        'auth' => '123456',
        'db' => 0
    ],
    'mongo' => [
        'host' => '192.168.250.1',
        'port' => 27017,
        'user' => 'admin',
        'pwd'  => 'admin',
        'db' => 'istore2'
    ]
];

return $cfg;