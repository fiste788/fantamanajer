<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\UserService;
use App\Stream\ActivityManager;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Authorization\Exception\ForbiddenException;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Utility\Hash;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Authentication->allowUnauthenticated(['login', 'publicKey', 'storeKey']);

        return parent::beforeFilter($event);
    }

    /**
     * Return current user
     *
     * @return void
     */
    public function current(): void
    {
        $this->set([
            'success' => true,
            'data' => $this->Authentication->getIdentity(),
        ]);

        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }

    /**
     * Get login Token
     *
     * @return void
     * @throws \Authentication\Authenticator\UnauthenticatedException
     * @throws \InvalidArgumentException
     */
    public function login(): void
    {
        $result = $this->Authentication->getResult();
        if ($result != null && $result->isValid()) {
            /** @var \App\Service\UserService $userService */
            $userService = $this->getContainer()->get(UserService::class);
            /** @var \App\Model\Entity\User $user */

            $user = $this->Authentication->getIdentity();
            $days = $this->request->getData('remember_me', false) ? 365 : 7;
            $this->set(
                [
                    'success' => true,
                    'data' => [
                        'token' => $userService->getToken((string)$user->id, $days),
                        'user' => $user->getOriginalData(),
                    ],
                ]
            );

            $this->viewBuilder()->setOption('serialize', ['data', 'success']);
        } elseif ($result != null) {
            //throw new UnauthenticatedException($this->Authentication->getResult()->getStatus());
            $this->response = $this->response->withStatus(401);
            $this->set(
                [
                    'success' => false,
                    'data' => [
                        'message' => $result->getStatus(),
                    ],
                ]
            );

            $this->viewBuilder()->setOption('serialize', ['data', 'success']);
        }
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout(): void
    {
        $this->Authentication->logout();
        $this->set(
            [
                'success' => true,
                'data' => true,
            ]
        );

        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }

    /**
     * Get activity stream
     *
     * @return void
     * @throws \GetStream\Stream\StreamFeedException
     * @throws \InvalidArgumentException
     * @throws \Authorization\Exception\ForbiddenException
     */
    public function stream(): void
    {
        $userId = (int)$this->request->getParam('user_id');
        $identity = $this->Authentication->getIdentity();
        if ($identity == null || $identity->getIdentifier() != $userId) {
            throw new ForbiddenException();
        }

        $page = (int)Hash::get($this->request->getQueryParams(), 'page', 1);
        $rowsForPage = 10;
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('user', (string)$userId, false, $offset, $rowsForPage);
        $this->set([
            'stream' => $stream,
        ]);

        $this->viewBuilder()->setOption('serialize', ['stream']);
    }

    /**
     * Undocumented function
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function edit(): ResponseInterface
    {
        $this->Crud->on('beforeSave', function (Event $event): void {
            $hasher = new DefaultPasswordHasher();

            /** @var \App\Model\Entity\User $user */
            $user = $event->getSubject()->entity;
            $user->password = $hasher->hash($user->password);
        });

        return $this->Crud->execute();
    }
}
