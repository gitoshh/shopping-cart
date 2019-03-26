<?php

return [

    /*
	| PDO Fetch Style
	*/
    'fetch' => PDO::FETCH_CLASS,

    /*
    | Default Database Connection Name
    */
    'default' => 'sqlite',

    /*
    | Database Connections
    */

    'connections' => [
        'sqlite' => [
            'driver'    => 'sqlite',
            'database'  => ':memory:',
            'prefix'   => env('DB_PREFIX', ''),
            ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'port'      => env('DB_PORT', 3306),
            'database'  => env('DB_DATABASE', 'forge'),
            'username'  => env('DB_USERNAME', 'forge'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => env('DB_CHARSET', 'utf8'),
            'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('DB_PREFIX', ''),
            'timezone'  => env('DB_TIMEZONE', '+00:00'),
            'strict'    => env('DB_STRICT_MODE', false),
            ],
        ],

    /*
	| Migration Repository Table
	*/
	'migrations' => 'migrations',

	/*
	| Redis Databases
	*/
	'redis' => [
    'cluster' => false,
    'default' => [
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 0,
        ]
    ]
];
