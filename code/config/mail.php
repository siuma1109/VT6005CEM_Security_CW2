<?php

return [
    'default' => 'smtp',
    'mailers' => [
        'smtp' => [
            'host' => 'mailhog',
            'port' => 1025,
            'encryption' => null,
            'username' => 'noreply@example.com',
            'password' => '1234',
        ]
    ],
    'from' => [
        'address' => 'noreply@example.com',
        'name' => 'Hong Kong Immigration Department',
    ],
];
