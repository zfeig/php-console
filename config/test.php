<?php 

$cfg = [
    'db' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'pwd' => 'root',
            'database' => 'istore2'
        ]
    ],
    'redis' => [
        'host' => '190.168.1.40',
        'port' => 6379,
        'auth' => 'ssssss',
        'db' => 0
    ]    
];

return $cfg;