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

use App\Command\DownloadPhotosCommand;
use App\Command\GetMatchdayScheduleCommand;
use App\Command\ResetPasswordCommand;
use App\Command\SendLineupsEmailCommand;
use App\Command\SendMissingLineupNotificationCommand;
use App\Command\SendTestNotificationCommand;
use App\Command\StartSeasonCommand;
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

        TypeFactory::map('acd', 'App\Database\Type\AttestedCredentialDataType');
        TypeFactory::map('ci', 'App\Database\Type\PublicKeyCredentialDescriptorType');
        TypeFactory::map('trust_path', 'App\Database\Type\TrustPathDataType');
        TypeFactory::map('simple_array', 'App\Database\Type\SimpleArrayDataType');
        TypeFactory::map('base64', 'App\Database\Type\Base64DataType');

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
            ->add(BodyParserMiddleware::class)
            ->add(new AuthenticationMiddleware($this))
            ->add(new AuthorizationMiddleware($this, [
                'requireAuthorizationCheck' => false,
                'identityDecorator' => function ($auth, $user) {
                    return $user->setAuthorization($auth);
                },
            ]))->add(new RequestAuthorizationMiddleware());

        return $middlewareQueue;
    }

    /**
     * Returns a service provider instance.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return \Authentication\AuthenticationServiceInterface
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
        $loginUrl = '/users/login';

        $service->setConfig('identityClass', User::class);
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

        // Load the authenticators
        /*$service->loadAuthenticator('Authentication.Session', [
            'fields' => $fields,
        ]);*/
        $service->loadAuthenticator('Authentication.Form', [
            'loginUrl' => $loginUrl,
            'fields' => $fields,
        ]);
        $service->loadAuthenticator('Authentication.Jwt', [
            'fields' => $fields,
            'returnPayload' => false,
        ]);

        return $service;
    }

    /**
     * Return the authorization provider instance
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return \Authorization\AuthorizationServiceInterface
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

        $commands->add('weekly_script', WeeklyScriptCommand::class);
        $commands->add('matchday update_date', UpdateMatchdayCommand::class);
        $commands->add('matchday update_calendar', UpdateCalendarCommand::class);
        $commands->add('matchday get_date', GetMatchdayScheduleCommand::class);
        $commands->add('send lineups', SendLineupsEmailCommand::class);
        $commands->add('send missing_lineup', SendMissingLineupNotificationCommand::class);
        $commands->add('send test_notification', SendTestNotificationCommand::class);
        $commands->add('utility download_photos', DownloadPhotosCommand::class);
        $commands->add('utility reset_password', ResetPasswordCommand::class);
        $commands->add('utility start_season', StartSeasonCommand::class);

        return $commands;
    }
}
