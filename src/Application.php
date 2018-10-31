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
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use DebugKit;
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
    public function bootstrap()
    {
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            try {
                $this->addPlugin('Bake');
                $this->addPlugin('IdeHelper');
            } catch (MissingPluginException $e) {
                // Do not halt if the plugin is missing
            }
        }

        $this->addPlugin('Authentication');
        $this->addPlugin('Authorization');
        $this->addPlugin('Crud');
        $this->addPlugin('Cors', ['bootstrap' => true, 'routes' => false]);
        $this->addPlugin('ADmad/JwtAuth');
        $this->addPlugin('Josegonzalez/Upload');
        $this->addPlugin('Migrations');
        $this->addPlugin('Bake');
        $this->addPlugin('CakeScheduler');
        $this->addPlugin('StreamCake');
        $this->addPlugin('DatabaseBackup', ['bootstrap' => true]);

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            //$this->addPlugin(DebugKit\Plugin::class);
        }
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param  MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware(null, Configure::read('Error')))
            ->add(new RoutingMiddleware($this, '_cake_routes_'))
            ->add(BodyParserMiddleware::class)
            ->add(new AuthenticationMiddleware($this))
            ->add(new AuthorizationMiddleware($this, [
                'requireAuthorizationCheck' => false,
                'identityDecorator' => function ($auth, $user) {
                    return $user->setAuthorization($auth);
                }
            ]))->add(new \Authorization\Middleware\RequestAuthorizationMiddleware());

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
        $map = new \Authorization\Policy\MapResolver();
        $map->map(\Cake\Http\ServerRequest::class, Policy\RequestPolicy::class);
        $orm = new OrmResolver();
        
        $resolver = new \Authorization\Policy\ResolverCollection([$orm, $map]);

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
