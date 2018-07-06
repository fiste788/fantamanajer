<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use App\Command\UpdateCalendarCommand;
use App\Command\UpdateMatchdayCommand;
use App\Command\WeeklyScriptCommand;
use App\Model\Entity\User;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Policy\OrmResolver;
use Cake\Console\CommandCollection;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Utility\Security;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface, \Authorization\AuthorizationServiceProviderInterface
{
    /**
     * Setup the middleware queue your application will use.
     *
     * @param  MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(ErrorHandlerMiddleware::class)

            // Handle plugin/theme assets like CakePHP normally does.
            //->add(AssetMiddleware::class)

            // Add routing middleware.
            // Routes collection cache enabled by default, to disable route caching
            // pass null as cacheConfig, example: `new RoutingMiddleware($this)`
            // you might want to disable this cache in case your routing is extremely simple
            ->add(new RoutingMiddleware($this, '_cake_routes_'))

            ->add(BodyParserMiddleware::class)

            ->add(new EncryptedCookieMiddleware(['CookieAuth'], Security::getSalt()))

            // Add the authetication middleware to the middleware queue
            ->add(new AuthenticationMiddleware($this))

            // Add authorization (after authentication if you are using that plugin too).
            ->add(new AuthorizationMiddleware($this, [
                'requireAuthorizationCheck' => false,
                'identityDecorator' => function ($auth, $user) {
                    return $user->setAuthorization($auth);
                }
            ]));

        return $middlewareQueue;
    }

    /**
     * Returns a service provider instance.
     *
     * @param ServerRequestInterface $request Request
     * @param ResponseInterface $response Response
     * @return AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request, ResponseInterface $response)
    {
        $service = new AuthenticationService();
        
        // Instantiate the service
        //$service = new AuthenticationService();
        $fields = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'email',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password'
        ];
        $loginUrl = '/users/login';

        $service->setConfig('identityClass', User::class);
        // Load identifiers
        $service->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
            'resolver' => [
                'className' => 'Authentication.Orm',
                'finder' => 'auth'
            ],
        ]);
        $service->loadIdentifier('Authentication.JwtSubject', [
            'resolver' => [
                'className' => 'Authentication.Orm',
                'finder' => 'auth'
            ],
        ]);

        // Load the authenticators
        $service->loadAuthenticator('Authentication.Session', [
            'fields' => $fields
        ]);
        $service->loadAuthenticator('Authentication.Form', [
            'loginUrl' => $loginUrl,
            'fields' => $fields
        ]);
        $service->loadAuthenticator('Authentication.Jwt', [
            'fields' => $fields,
            'returnPayload' => false
        ]);

        return $service;
    }
    
    public function getAuthorizationService(ServerRequestInterface $request, ResponseInterface $response)
    {
        $resolver = new OrmResolver();

        return new AuthorizationService($resolver);
    }
    
    /**
     * Define the console commands for an application.
     *
     * @param CommandCollection $commands The CommandCollection to add commands into.
     * @return CommandCollection The updated collection.
     */
    public function console($commands)
    {
        $commands->add('weekly_script', WeeklyScriptCommand::class);
        $commands->add('matchday update', UpdateMatchdayCommand::class);
        $commands->add('matchday update_calendar', UpdateCalendarCommand::class);

        $commands->addMany($commands->autoDiscover());

        return $commands;
    }
}
