<?php

/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * recieves a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\InflectedRoute;
use Cake\Routing\RouteBuilder;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 */

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setRouteClass(InflectedRoute::class);

$routes->scope('/', function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes->resources('Articles', [
        'only' => ['create', 'update', 'delete', 'view'],
    ]);

    $routes->connect('/members/best', [
        'controller' => 'Members',
        'action' => 'best',
    ]);

    $routes->connect('/members/{id}', [
        'controller' => 'Members',
        'action' => 'view',
    ])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);

    $routes->resources('Users', [
        'only' => ['update', 'login', 'current', 'logout'],
        'map' => [
            'login' => [
                'action' => 'login',
                'method' => 'POST',
                '_name' => 'login'
            ],
            'logout' => [
                'action' => 'logout',
                'method' => 'GET',
            ],
            'current' => [
                'action' => 'current',
                'method' => 'GET',
            ],
        ],
    ], function (RouteBuilder $routes) {
        $routes->resources('PublicKeyCredentialSources', [
            'prefix' => 'Users',
            'only' => ['index', 'delete'],
        ]);
        $routes->connect('/stream', [
            'controller' => 'Users',
            'action' => 'stream',
        ]);
    });
    $routes->resources('Webauthn', [
        'only' => ['request', 'create', 'login', 'register'],
        'map' => [
            'request' => [
                'action' => 'publicKeyRequest',
                'method' => 'GET',
                'path' => 'login',
            ],
            'create' => [
                'action' => 'publicKeyCreation',
                'method' => 'GET',
                'path' => 'register',
            ],
            'login' => [
                'controller' => 'user',
                'action' => 'login',
                'method' => 'POST',
            ],
            'register' => [
                'action' => 'register',
                'method' => 'POST',
            ],
        ],
    ]);

    $routes->resources('Players', [
        'only' => 'view',
    ]);

    $routes->resources('Members', [
        'only' => 'view',
    ], function (RouteBuilder $routes) {
        $routes->connect('/ratings', [
            'controller' => 'Ratings',
            'prefix' => 'members'
        ]);
    });

    $routes->resources('Clubs', [
        'only' => ['index', 'view'],
    ], function (RouteBuilder $routes) {
        $routes->resources('Members', [
            'prefix' => 'Clubs',
            'only' => 'index',
        ]);

        $routes->resources('Stream', [
            'prefix' => 'Clubs',
            'only' => 'index',
        ]);
    });

    $routes->resources('Scores', [
        'only' => ['view', 'update'],
    ]);

    $routes->resources('Matchdays', [
        'only' => ['view', 'current'],
        'map' => [
            'current' => [
                'action' => 'current',
                'method' => 'GET',
            ],
        ],
    ]);

    $routes->resources('PushSubscriptions', [
        'only' => ['view', 'update', 'create'],
    ]);

    $routes->connect('/push-subscriptions/:id', [
        'controller' => 'PushSubscriptions',
        'action' => 'delete',
    ])
        ->setMethods(['DELETE'])
        ->setPatterns(['id' => '[A-Fa-f0-9]{64}'])
        ->setPass(['id']);;

    $routes->resources('Championships', [
        'only' => ['view', 'update'],
    ], function (RouteBuilder $routes) {
        $routes->resources('Articles', [
            'only' => 'index',
            'prefix' => 'championships',
        ]);

        $routes->resources('Scores', [
            'prefix' => 'championships',
            'only' => 'index',
            'path' => 'ranking',
        ]);

        $routes->resources('Teams', [
            'prefix' => 'championships',
            'only' => 'index',
        ]);

        $routes->resources('Stream', [
            'prefix' => 'championships',
            'only' => 'index',
        ]);

        $routes->connect('/members/free', [
            'controller' => 'Members',
            'action' => 'free',
            'prefix' => 'championships',
        ]);

        $routes->connect('/members/free/:role_id', [
            'controller' => 'Members',
            'action' => 'freeByRole',
            'prefix' => 'championships',
        ])
            ->setPatterns(['role_id' => '\d+']);
    });

    $routes->prefix('admin', [], function (RouteBuilder $routes) {
        $routes->resources('Transferts', [
            'only' => ['create'],
        ]);
    });

    $routes->resources('Teams', [
        'only' => ['view', 'update', 'create'],
    ], function (RouteBuilder $routes) {
        $routes->resources('Articles', [
            'only' => 'index',
            'prefix' => 'teams',
        ]);

        $routes->resources('Members', [
            'only' => 'index',
            'prefix' => 'Teams',
        ]);

        $routes->connect('/members/not_mine/{role_id}', [
            'controller' => 'Members',
            'action' => 'notMine',
            'prefix' => 'Teams',
        ])
            ->setPatterns(['role_id' => '\d+'])
            ->setPass(['role_id']);

        $routes->resources('Selections', [
            'only' => ['index', 'create'],
            'prefix' => 'Teams',
        ]);

        $routes->resources('Transferts', [
            'only' => ['index'],
            'prefix' => 'Teams',
        ]);

        $routes->connect('/notifications', [
            'controller' => 'notifications',
            'action' => 'index',
            'prefix' => 'Teams',
        ]);

        $routes->connect('/notifications/count', [
            'controller' => 'notifications',
            'action' => 'count',
            'prefix' => 'Teams',
        ]);

        $routes->resources('Lineups', [
            'prefix' => 'teams',
            'only' => ['current', 'create', 'update', 'likely'],
            'map' => [
                'current' => [
                    'action' => 'current',
                    'method' => 'GET',
                ],
                'likely' => [
                    'action' => 'likely',
                    'method' => 'GET',
                ],
            ],
        ]);

        $routes->resources('Scores', [
            'prefix' => 'teams',
            'only' => ['last', 'viewByMatchday', 'index'],
            'map' => [
                'last' => [
                    'action' => 'last',
                ],
            ],
        ]);

        $routes->resources('Stream', [
            'prefix' => 'teams',
            'only' => 'index',
        ]);

        $routes->connect('/scores/{matchday_id}', [
            'controller' => 'Scores',
            'action' => 'viewByMatchday',
            'prefix' => 'teams',
        ])
            ->setPatterns(['matchday_id' => '\d+'])
            ->setPass(['matchday_id']);
    });
    // $routes->fallbacks(InflectedRoute::class);
});
