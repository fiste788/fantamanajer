<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
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
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);
    $routes->resources('Articles', [
        'only' => ['create', 'update', 'delete', 'view']
    ]);
    $routes->connect('/members/best', [
        'controller' => 'Members',
        'action' => 'best'
    ]);
    $routes->resources('Users', [
        'only' => ['update', 'login', 'current', 'logout'],
        'map' => [
            'login' => [
                'action' => 'login',
                'method' => 'POST'
            ],
            'logout' => [
                'action' => 'logout',
                'method' => 'GET'
            ],
            'current' => [
                'action' => 'current',
                'method' => 'GET'
            ]
        ]
    ]);
    $routes->resources('Players', [
        'only' => 'view'
    ]);
    $routes->resources('Clubs', [
            'only' => ['index', 'view']
        ], function (RouteBuilder $routes) {
            $routes->resources('Members', [
                'prefix' => 'Clubs',
                'only' => 'index'
            ]);
        });
    $routes->resources('Scores', [
        'only' => 'view'
    ]);
    $routes->resources('Matchdays', [
        'only' => ['view', 'current'],
        'map' => [
            'current' => [
                'action' => 'current',
                'method' => 'GET'
            ]
        ]
    ]);
    $routes->resources('PushSubscriptions', [
        'only' => ['view', 'delete', 'update', 'create']
    ]);
    $routes->resources('Championships', [
            'only' => ['view']
        ], function (RouteBuilder $routes) {
            $routes->resources('Articles', [
            'only' => 'index',
            'prefix' => 'championships'
            ]);
            $routes->resources('Events', [
            'prefix' => 'championships',
            'only' => 'index'
            ]);
            $routes->resources('Scores', [
            'prefix' => 'championships',
            'only' => 'index',
            'path' => 'ranking'
            ]);
            $routes->resources('Teams', [
            'prefix' => 'championships',
            'only' => 'index'
            ]);

            $routes->connect('/members/free/:role_id', [
                'controller' => 'Members',
                'action' => 'free',
                'prefix' => 'championships'
            ], [
                'role_id' => '\d+'
            ]);
        });
    $routes->resources('Teams', [
            'only' => ['view', 'update']
        ], function (RouteBuilder $routes) {
            $routes->resources('Articles', [
            'only' => 'index',
            'prefix' => 'teams'
            ]);
            $routes->resources('Members', [
            'only' => 'index',
            'prefix' => 'Teams'
            ]);
            $routes->resources('Selections', [
            'only' => ['index', 'create'],
            'prefix' => 'Teams'
            ]);
            $routes->resources('Transferts', [
            'only' => 'index',
            'prefix' => 'Teams'
            ]);
            $routes->resources('Notifications', [
            'only' => 'index',
            'prefix' => 'Teams'
            ]);
            $routes->resources('Lineups', [
            'prefix' => 'teams',
            'only' => ['current', 'create', 'update'],
            'map' => [
                'current' => [
                    'action' => 'current',
                    'method' => 'GET'
                ]
            ]
            ]);
            $routes->resources('Scores', [
            'prefix' => 'teams',
            'only' => ['last', 'viewByMatchday'],
            'map' => [
                'last' => [
                    'action' => 'last'
                ],
            ]
            ]);
            $routes->connect('/scores/:matchday_id', [
                'controller' => 'Scores',
                'action' => 'viewByMatchday',
                'prefix' => 'teams'
            ], [
                'matchday_id' => '\d+',
                'pass' => ['matchday_id']
            ]);
        });
    //$routes->fallbacks(DashedRoute::class);
});