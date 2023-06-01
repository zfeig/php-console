<?php 

$cfg = [
    'db' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'pwd' => 'root',
            'prefix' => '',
            'database' => 'istore2'
        ]
    ],
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'auth' => '123456',
        'db' => 0
    ]    
];

return $cfg;