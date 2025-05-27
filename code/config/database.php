<?php

return [
    'default' => 'pgsql',
    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => 'postgres',
            'database' => 'security_cw2',
            'username' => 'root',
            'password' => '1234',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'port' => 5432,
        ],
    ],
];
