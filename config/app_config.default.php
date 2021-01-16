<?php
return [
    'Cors' => [
        'AllowOrigin' => [
            'https://fantamanajer.it',
            'https://dev.fantamanajer.it',
            'http://localhost:4200',
            'http://127.0.0.1:8080',
        ],
        'AllowCredentials' => true,
        'AllowMethods' => [
            'GET',
            'POST',
            'PUT',
            'PATCH',
            'DELETE',
        ],
        'AllowHeaders' => [
            'Origin',
            'X-Requested-With',
            'Content-Type',
            'Authorization',
            'Access-Control-Allow-Headers',
            'X-Http-Method-Override',
        ],
        'ExposeHeaders' => true,
        'MaxAge' => (24 * 60 * 60),
        'exceptionRenderer' => false,
    ],

    'WebPush' => [
        'VAPID' => [
            'subject' => 'http://fantamanajer.it',
            'publicKey' => 'publicKey',
            'privateKey' => 'privateKey',
        ],
    ],

    'WebPushMessage' => [
        'default' => [
            'badge' => '/assets/icons/icon-monochrome.png',
            'icon' => '/assets/icons/android-chrome-192x192.png',
            'lang' => 'it-IT',
        ],
    ],

    'GetStream' => [
        'default' => [
            'appId' => '11111',
            'appKey' => 'abcdefghi',
            'appSecret' => 'secret',
        ],
    ],

    'DatabaseBackup' => [
        'chmod' => 0664,
        'target' => RESOURCES . 'backups',
    ],

    'Webauthn' => [
        'id' => 'localhost',
        'name' => 'FantaManajer',
        'icon' => 'https://fantamanajer.it/favicon.ico',
        'safetyNetKey' => 'keys'
    ]
];
