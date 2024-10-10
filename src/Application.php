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
use App\Command\SendTestNotificationCommand;
use App\Database\Type as Types;
use App\Model\Entity\User;
use App\Service\ComputeScoreService;
use App\Service\DownloadRatingsService;
use App\Service\LikelyLineupService;
use App\Service\LineupService;
use App\Service\NotificationSubscriptionService;
use App\Service\PublicKeyCredentialSourceRepositoryService;
use App\Service\PushNotificationService;
use App\Service\RatingService;
use App\Service\ScoreService;
use App\Service\SelectionService;
use App\Service\TeamService;
use App\Service\TransfertService;
use App\Service\UpdateMemberService;
use App\Service\UserService;
use App\Service\WebauthnService;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\AbstractIdentifier;
use Authentication\Middleware\AuthenticationMiddleware;
use Authentication\Plugin as AuthenticationPlugin;
use Authorization\AuthorizationPlugin;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Middleware\RequestAuthorizationMiddleware;
use Authorization\Policy\MapResolver;
use Authorization\Policy\OrmResolver;
use Authorization\Policy\ResolverCollection;
use Bake\BakePlugin;
use Cake\Console\CommandCollection;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Database\TypeFactory;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router;
use CakePreloader\Plugin as CakePreloaderPlugin;
use CakeScheduler\CakeSchedulerPlugin;
use Crud\CrudPlugin;
//use DatabaseBackup\Plugin as DatabaseBackupPlugin;
use IdeHelper\IdeHelperPlugin;
use Josegonzalez\Upload\UploadPlugin;
use League\Container\ReflectionContainer;
use Migrations\MigrationsPlugin;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 *
 * @extends \Cake\Http\BaseApplication<\App\Application>
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
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        TypeFactory::map('simple_array', Types\SimpleArrayDataType::class);
        TypeFactory::map('base64', Types\Base64DataType::class);

        $this->addPlugin(AuthenticationPlugin::class);
        $this->addPlugin(AuthorizationPlugin::class);
        $this->addPlugin(CrudPlugin::class);
        $this->addPlugin(UploadPlugin::class);
        //$this->addPlugin('CakeScheduler');
        $this->addPlugin('StreamCake');
        //$this->addPlugin(DatabaseBackupPlugin::class);
        //$this->addPlugin(CakePreloaderPlugin::class);

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
        /** @var array<array-key, mixed> $config */
        $config = Configure::read('Error');
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware($config, $this))
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
            AbstractIdentifier::CREDENTIAL_USERNAME => 'email',
            AbstractIdentifier::CREDENTIAL_PASSWORD => 'password',
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
        $container->add(ComponentRegistry::class);
        $container->delegate(
            new ReflectionContainer(true)
        );

        $container->add(SendTestNotificationCommand::class)->addArgument(PushNotificationService::class);

        $container->add(ComputeScoreService::class);
        $container->add(DownloadRatingsService::class);
        $container->extend(DownloadRatingsService::class)->addArgument('io');
        $container->add(LikelyLineupService::class);
        $container->add(LineupService::class);
        $container->add(NotificationSubscriptionService::class);
        $container->add(PublicKeyCredentialSourceRepositoryService::class);
        $container->addShared(PushNotificationService::class);
        $container->add(RatingService::class);
        $container->add(ScoreService::class);
        $container->add(SelectionService::class);
        $container->add(TeamService::class);
        $container->add(TransfertService::class);
        $container->add(UpdateMemberService::class);
        $container->add(UserService::class);
        $container->add(WebauthnService::class);
    }

    /**
     * @inheritDoc
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin(BakePlugin::class);
        $this->addPlugin(CakeSchedulerPlugin::class);
        $this->addPlugin(CakePreloaderPlugin::class);

        if (Configure::read('debug')) {
            $this->addOptionalPlugin(MigrationsPlugin::class);
            $this->addOptionalPlugin(IdeHelperPlugin::class);
        }
        // Load more plugins here
    }
}
