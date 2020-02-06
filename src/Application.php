<?php
declare(strict_types=1);

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

use App\Authentication\Authenticator\WebauthnAuthenticator;
use App\Authentication\Identifier\WebauthnHandleIdentifier;
use App\Command as Commands;
use App\Database\Type as Types;
use App\Model\Entity\User;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Middleware\RequestAuthorizationMiddleware;
use Authorization\Policy\MapResolver;
use Authorization\Policy\OrmResolver;
use Authorization\Policy\ResolverCollection;
use Cake\Console\CommandCollection;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Database\TypeFactory;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Http\ServerRequest;
use Cake\Routing\Middleware\RoutingMiddleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements
    AuthenticationServiceProviderInterface,
    AuthorizationServiceProviderInterface
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

        TypeFactory::map('acd', Types\AttestedCredentialDataType::class);
        TypeFactory::map('ci', Types\PublicKeyCredentialDescriptorType::class);
        TypeFactory::map('trust_path', Types\TrustPathDataType::class);
        TypeFactory::map('simple_array', Types\SimpleArrayDataType::class);
        TypeFactory::map('base64', Types\Base64DataType::class);

        $this->addPlugin('Authentication');
        $this->addPlugin('Authorization');
        $this->addPlugin('Crud');
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
            $this->addPlugin(\DebugKit\Plugin::class);
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
            ->add(new ErrorHandlerMiddleware((array)Configure::read('Error')))
            ->add(new RoutingMiddleware($this, '_cake_routes_'))
            ->add(new BodyParserMiddleware())
            ->add(new AuthenticationMiddleware($this))
            ->add(new AuthorizationMiddleware($this, [
                'requireAuthorizationCheck' => false,
                'identityDecorator' => function (AuthorizationServiceInterface $auth, User $user) {
                    return $user->setAuthorization($auth);
                },
            ]))->add(new RequestAuthorizationMiddleware());

        return $middlewareQueue;
    }

    /**
     * Returns a service provider instance.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     *
     * @return \Authentication\AuthenticationService
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();

        // Instantiate the service
        //$service = new AuthenticationService();
        $fields = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'email',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
        ];

        $service->setConfig('identityClass', \App\Model\Entity\User::class);
        // Load identifiers
        $service->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
            'resolver' => [
                'className' => 'Authentication.Orm',
                'finder' => 'auth',
            ],
        ]);
        $service->loadIdentifier('Authentication.JwtSubject', [
            'resolver' => [
                'className' => 'Authentication.Orm',
                'finder' => 'auth',
            ],
        ]);
        $service->loadIdentifier('Authentication.WebauthnHandle', [
            'className' => WebauthnHandleIdentifier::class,
            'resolver' => [
                'className' => 'Authentication.Orm',
                'finder' => 'auth',
            ],
        ]);

        // Load the authenticators
        /*$service->loadAuthenticator('Authentication.Session', [
            'fields' => $fields,
        ]);*/
        $service->loadAuthenticator('Authentication.Form', [
            'loginUrl' => [
                '/users/login',
            ],
            'fields' => $fields,
            //'urlChecker' => CakeRouterUrlChecker::class,

        ]);
        $service->loadAuthenticator('Authentication.Jwt', [
            'fields' => $fields,
            'returnPayload' => false,
        ]);

        $service->loadAuthenticator('Authentication.Webauthn', [
            'className' => WebauthnAuthenticator::class,
            'loginUrl' => [
                '/webauthn/login',
            ],
            'fields' => $fields,
        ]);

        return $service;
    }

    /**
     * Return the authorization provider instance
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     *
     * @return \Authorization\AuthorizationService
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
     * @param \Cake\Console\CommandCollection $commands The CommandCollection to add commands into.
     * @return \Cake\Console\CommandCollection The updated collection.
     */
    public function console($commands): CommandCollection
    {
        $commands->addMany($commands->autoDiscover());

        $commands->add('weekly_script', Commands\WeeklyScriptCommand::class);
        $commands->add('matchday update_date', Commands\UpdateMatchdayCommand::class);
        $commands->add('matchday update_calendar', Commands\UpdateCalendarCommand::class);
        $commands->add('matchday get_date', Commands\GetMatchdayScheduleCommand::class);
        $commands->add('send lineups', Commands\SendLineupsEmailCommand::class);
        $commands->add('send missing_lineup', Commands\SendMissingLineupNotificationCommand::class);
        $commands->add('send test_notification', Commands\SendTestNotificationCommand::class);
        $commands->add('utility download_photos', Commands\DownloadPhotosCommand::class);
        $commands->add('utility reset_password', Commands\ResetPasswordCommand::class);
        $commands->add('utility start_season', Commands\StartSeasonCommand::class);

        return $commands;
    }
}
