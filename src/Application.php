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
use App\Service\ComputeScoreService;
use App\Service\LineupService;
use App\Service\PublicKeyCredentialSourceRepositoryService;
use App\Service\PushNotificationService;
use App\Service\UserService;
use App\Service\WebauthnService;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authentication\Plugin as AuthenticationPlugin;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Middleware\RequestAuthorizationMiddleware;
use Authorization\Plugin as AuthorizationPlugin;
use Authorization\Policy\MapResolver;
use Authorization\Policy\OrmResolver;
use Authorization\Policy\ResolverCollection;
use Bake\Plugin as BakePlugin;
use Cake\Console\CommandCollection;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Core\Exception\MissingPluginException;
use Cake\Database\TypeFactory;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Http\ServerRequest;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router;
use CakePreloader\Plugin as CakePreloaderPlugin;
use Crud\Plugin as CrudPlugin;
use DatabaseBackup\Plugin as DatabaseBackupPlugin;
use IdeHelper\Plugin;
use Josegonzalez\Upload\Plugin as UploadPlugin;
use Migrations\Plugin as MigrationsPlugin;
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
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            try {
                $this->addPlugin(BakePlugin::class);
                $this->addPlugin(Plugin::class);
            } catch (MissingPluginException $e) {
                // Do not halt if the plugin is missing
            }
        }

        TypeFactory::map('acd', Types\AttestedCredentialDataType::class);
        TypeFactory::map('ci', Types\PublicKeyCredentialDescriptorType::class);
        TypeFactory::map('trust_path', Types\TrustPathDataType::class);
        TypeFactory::map('simple_array', Types\SimpleArrayDataType::class);
        TypeFactory::map('base64', Types\Base64DataType::class);

        $this->addPlugin(AuthenticationPlugin::class);
        $this->addPlugin(AuthorizationPlugin::class);
        $this->addPlugin(CrudPlugin::class);
        $this->addPlugin(UploadPlugin::class);
        $this->addPlugin(MigrationsPlugin::class);
        $this->addPlugin(BakePlugin::class);
        $this->addPlugin('CakeScheduler');
        $this->addPlugin('StreamCake');
        $this->addPlugin(DatabaseBackupPlugin::class);
        $this->addPlugin(CakePreloaderPlugin::class);

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            //$this->addPlugin(\DebugKit\Plugin::class);
        }
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     * @throws \InvalidArgumentException
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware((array)Configure::read('Error')))
            ->add(new RoutingMiddleware($this))
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
     * @return \Authentication\AuthenticationService
     * @throws \Cake\Core\Exception\CakeException
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        // Instantiate the service
        $service = new AuthenticationService();

        $fields = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'email',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
        ];

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
                Router::url([
                    'controller' => 'Users',
                    'action' => 'login',
                    '_method' => 'POST',
                    'prefix' => false,
                ]),
            ],
            'fields' => $fields,
        ]);
        $service->loadAuthenticator('Authentication.Jwt', [
            'fields' => $fields,
            'algorithm' => 'HS256',
            'returnPayload' => false,
        ]);

        $service->loadAuthenticator('Authentication.Webauthn', [
            'className' => WebauthnAuthenticator::class,
            'loginUrl' => [
                Router::url([
                    'controller' => 'Webauthn',
                    'action' => 'login',
                    '_method' => 'POST',
                    'prefix' => false,
                ]),
            ],
            'fields' => $fields,
        ]);

        return $service;
    }

    /**
     * Return the authorization provider instance
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return \Authorization\AuthorizationService
     * @throws \InvalidArgumentException
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
     * @throws \InvalidArgumentException
     */
    public function console(CommandCollection $commands): CommandCollection
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

    /**
     * Define the containers for an application.
     *
     * @param \Cake\Core\ContainerInterface $container container
     * @return void
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(PublicKeyCredentialSourceRepositoryService::class);
        $container->add(WebauthnService::class);
        $container->add(UserService::class);
        $container->add(ComputeScoreService::class);
        $container->add(LineupService::class);
        $container->addShared(PushNotificationService::class);
    }
}
