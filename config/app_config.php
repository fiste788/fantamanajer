<?php
return [
    'Cors' => [
        'AllowOrigin' => ['https://fantamanajer.it', 'https://dev.fantamanajer.it', 'http://localhost:4200', 'http://127.0.0.1:8080'], // accept all origin
        'AllowCredentials' => true,
        'AllowMethods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], // accept all HTTP methods
        'AllowHeaders' => ['Origin', 'X-Requested-With', 'Content-Type', 'Authorization', 'Access-Control-Allow-Headers', 'X-Http-Method-Override'], // accept all headers
        'ExposeHeaders' => false, // don't accept personal headers
        'MaxAge' => 86400, // cache for 1 day
    ],

    'App.paths.images' => [
        'clubs' => WWW_ROOT . 'img' . DS . 'Clubs' . DS,
        'teams' => WWW_ROOT . 'files' . DS . 'upload' . DS . 'teams' . DS,
        'players' => WWW_ROOT . 'img' . DS . 'players' . DS,
    ],

    'WebPush' => [
        'VAPID' => [
            'subject' => 'http://fantamanajer.it',
            'publicKey' => 'BEtTz3mWJt9vnMu759pONVf-KeKBv2isIgpfuCgpm_cxqBTwwUyS_eI6Dx7tKuutl0DzgYARKG6vuhfAszr5JBw',
            'privateKey' => 'gFfsi6IV_GPTaJImIo5xLJRJ3u_gL8eL1fMr7JsxrZ0'
        ]
    ],

    'WebPushMessage' => [
        'default' => [
            'badge' => '/assets/icon-monochrome.png',
            'icon' => '/assets/android-chrome-192x192.png',
            'lang' => 'it',
            'renotify' => true,
            'requireInteraction' => true
        ]
    ]
];
