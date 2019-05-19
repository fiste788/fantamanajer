<?php
declare (strict_types = 0);
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
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Middleware\RequestAuthorizationMiddleware;
use Authorization\Policy\MapResolver;
use Authorization\Policy\OrmResolver;
use Authorization\Policy\ResolverCollection;
use Cake\Console\CommandCollection;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Database\Type;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\ServerRequest;
use Cake\Routing\Middleware\RoutingMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Authorization\AuthorizationServiceInterface;
use Cors\Routing\Middleware\CorsMiddleware;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface, AuthorizationServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap(): void
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
        /*
        Type::map('acd', 'App\Database\Type\AttestedCredentialDataType');
        Type::map('ci', 'App\Database\Type\PublicKeyCredentialDescriptorType');
        Type::map('trust_path', 'App\Database\Type\TrustPathDataType');
        Type::map('simple_array', 'App\Database\Type\SimpleArrayDataType');
        Type::map('base64', 'App\Database\Type\Base64DataType');
        */

        $this->addPlugin('Authentication');
        $this->addPlugin('Authorization');
        $this->addPlugin('Crud');
        $this->addPlugin('Cors', ['bootstrap' => true, 'routes' => false]);
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
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware(null, Configure::read('Error')))
            ->add(new RoutingMiddleware($this, '_cake_routes_'))
            //->add(new CorsMiddleware())
            ->add(BodyParserMiddleware::class)
            ->add(new AuthenticationMiddleware($this))
            ->add(new AuthorizationMiddleware($this, [
                'requireAuthorizationCheck' => false,
                'identityDecorator' => function ($auth, $user) {
                    return $user->setAuthorization($auth);
                }
            ]))->add(new RequestAuthorizationMiddleware());

        return $middlewareQueue;
    }

    /**
     * Returns a service provider instance.
     *
     * @param ServerRequestInterface $request Request
     * @return AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
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

    /**
     * Return the authorization provider instance
     *
     * @param ServerRequestInterface $request Request
     * @return AuthorizationServiceInterface
     */
    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
    {
        $map = new MapResolver();
        $map->map(ServerRequest::class, Policy\RequestPolicy::class);
        $orm = new OrmResolver();

        $resolver = new ResolverCollection([$orm, $map]);

        return new AuthorizationService($resolver);
    }

    /**
     * Define the console commands for an application.
     *
     * @param CommandCollection $commands The CommandCollection to add commands into.
     * @return CommandCollection The updated collection.
     */
    public function console($commands): CommandCollection
    {
        $commands->add('weekly_script', WeeklyScriptCommand::class);
        $commands->add('matchday update', UpdateMatchdayCommand::class);
        $commands->add('matchday update_calendar', UpdateCalendarCommand::class);

        $commands->addMany($commands->autoDiscover());

        return $commands;
    }
}
