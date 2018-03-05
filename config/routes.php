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

Router::prefix('api', function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);
    $routes->resources('Articles', [
        'only' => ['create', 'update', 'delete', 'view']
    ]);
    $routes->resources('Users', [
        'only' => ['view', 'update']
    ]);
    $routes->resources('Players', [
        'only' => 'view'
    ]);
    $routes->resources('Clubs', function (RouteBuilder $routes) {
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
                'method' => 'get'
            ]
        ]
    ]);
    $routes->resources('Subscriptions', [
        'path' => 'webpush',
        'map' => [
            'delete/:token' => [
                'action' => 'deleteByEndpoint',
                'method' => 'DELETE'
            ]
        ]
    ]);
    $routes->resources('Championships', function (RouteBuilder $routes) {
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
                'prefix' => 'api/championships'
            ], [
                'role_id' => '\d+'
        ]);
    });
    $routes->resources('Teams', function (RouteBuilder $routes) {
        $routes->resources('Articles', [
            'only' => 'index',
            'prefix' => 'teams'
        ]);
        $routes->resources('Members', [
            'only' => 'index',
            'prefix' => 'Teams'
        ]);
        $routes->resources('Selections',  [
            'only' => ['index', 'create'],
            'prefix' => 'Teams'
        ]);
        $routes->resources('Transferts',  [
            'only' => 'index',
            'prefix' => 'Teams'
        ]);
        $routes->resources('Notifications',  [
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
                'prefix' => 'api/teams'
            ], [
                ':matchday_id' => '\d+',
                'pass' => ['matchday_id']
        ]);
    });
    $routes->fallbacks(DashedRoute::class);
});

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login'], ['_name' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout'], ['_name' => 'logout']);
    $routes->connect('/users/*', ['controller' => 'Users', 'action' => 'view']);
    $routes->connect('/users/edit/*', ['controller' => 'Users', 'action' => 'edit']);

    $routes->connect('/players/*', ['controller' => 'Players', 'action' => 'view']);

    $routes->connect('/clubs', ['controller' => 'Clubs', 'action' => 'index'], ['_name' => 'clubs_index']);
    $routes->connect('/clubs/*', ['controller' => 'Clubs', 'action' => 'view'], ['_name' => 'clubs_view']);

    $routes->connect('/leagues', ['controller' => 'Leagues', 'action' => 'index']);
    $routes->connect('/leagues/*', ['controller' => 'Leagues', 'action' => 'view']);
    $routes->scope('/events', function ($routes) {
        $routes->extensions(['rss']);
        $routes->connect('/', ['controller' => 'Events', 'action' => 'index'], ['_name' => 'events']);
    });

    /*
      $routes->connect('/teams', ['controller' => 'Teams', 'action' => 'index'], ['_name' => 'teams_index']);
      $routes->connect('/teams/:id', ['controller' => 'Teams', 'action' => 'view'], ['_name' => 'teams_view', 'id' => '\d+', 'pass' => ['id']]);
      $routes->connect('/teams/:id/players', ['controller' => 'Members', 'action' => 'index'], ['_name' => 'Team.members', 'id' => '\d+', 'pass' => ['id']]);
      $routes->connect('/teams/:id/transferts', ['controller' => 'Transferts', 'action' => 'index'], ['_name' => 'Team.transferts', 'id' => '\d+', 'pass' => ['id']]);
     */

    $routes->scope('/teams', function (RouteBuilder $routes) {
        $routes->connect('/edit/:id', ['controller' => 'Teams', 'action' => 'edit'], ['_name' => 'teams_edit', 'id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/', ['controller' => 'Teams', 'action' => 'index'], ['_name' => 'teams_index']);
        $routes->connect('/:id', ['controller' => 'Teams', 'action' => 'view'], ['_name' => 'teams_view', 'id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:id/players', ['controller' => 'Members', 'action' => 'index'], ['_name' => 'Team.members', 'id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:id/transferts', ['controller' => 'Transferts', 'action' => 'index'], ['_name' => 'Team.transferts', 'id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:id/articles', ['controller' => 'Articles', 'action' => 'indexByTeam'], ['_name' => 'Team.articles', 'id' => '\d+', 'pass' => ['id']]);
    });

    $routes->scope('/championships', function ($routes) {
        $routes->connect('/:id', ['controller' => 'Championships', 'action' => 'view'], ['_name' => 'championships_view', 'id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:id/ranking', ['controller' => 'Scores', 'action' => 'index'], ['_name' => 'Championship.ranking', 'id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:id/teams', ['controller' => 'Teams', 'action' => 'index'], ['_name' => 'Championship.teams', 'id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:id/articles', ['controller' => 'Articles', 'action' => 'indexByTeam'], ['_name' => 'Championship.articles', 'id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:id/free_player', ['controller' => 'Members', 'action' => 'free'], ['_name' => 'Championship.freePlayer', 'id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:id/free_player/:role_id', ['controller' => 'Members', 'action' => 'free'], ['_name' => 'Championship.freePlayer.role', 'id' => '\d+', 'role_id' => '\d+', 'pass' => ['id', 'role_id']]);
    });

    $routes->connect('/lineup/*', ['controller' => 'Lineups', 'action' => 'view'], ['_name' => 'lineups']);

    //$routes->connect('/classification', ['controller' => 'Points', 'action' => 'index']);
    $routes->connect('/scores/:matchday_id/:team_id', [
        'controller' => 'Scores',
        'action' => 'view'
            ], [
        '_name' => 'Scores.view',
        'matchday_id' => '[0-9]+',
        'team_id' => '[0-9]+',
        'pass' => ['matchday_id', 'team_id']
            ]);

    /*
      $routes->connect('/championships/:championship_id/classification',[
      'controller' => 'Points',
      'action' => 'index'
      ],[
      'championship_id' => '[0-9]+',
      'pass' => ['championship_id'],
      '_name' => 'classification'
      ]);

      $routes->connect('/championships/:championship_id/articles',[
      'controller' => 'Articles',
      'action' => 'index'
      ],[
      'championship_id' => '[0-9]+',
      'pass' => ['championship_id'],
      '_name' => 'articles'
      ]); */

    //$routes->resources('Articles');
    //$routes->resources('Clubs');


    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('DashedRoute');
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
