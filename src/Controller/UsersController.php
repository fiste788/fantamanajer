<?php
declare(strict_types=1);

namespace App\Controller;

use App\Stream\ActivityManager;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Utility\Hash;

/**
 * @property \App\Service\UserService $User
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    use ServiceAwareTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('User');
        $this->loadService('Credential');
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Authentication->allowUnauthenticated(['login', 'publicKey', 'storeKey']);
    }

    /**
     * Return current user
     *
     * @return void
     */
    public function current()
    {
        $this->set([
            'success' => true,
            'data' => $this->Authentication->getIdentity(),
            '_serialize' => ['success', 'data'],
        ]);
    }

    /**
     * Get login Token
     *
     * @return void
     *
     * @throws \Authentication\Authenticator\UnauthenticatedException
     */
    public function login()
    {
        $result = $this->Authentication->getResult();
        if ($result != null && $result->isValid()) {
            /** @var \App\Model\Entity\User $user */
            $user = $this->Authentication->getIdentity();
            $days = $this->request->getData('remember_me', false) ? 365 : 7;
            $this->set(
                [
                    'success' => true,
                    'data' => [
                        'token' => $this->User->getToken((string)$user->id, $days),
                        'user' => $user->getOriginalData(),
                    ],
                    '_serialize' => ['success', 'data'],
                ]
            );
        } elseif ($result != null) {
            //throw new UnauthenticatedException($this->Authentication->getResult()->getStatus());
            $this->response = $this->response->withStatus(401);
            $this->set(
                [
                    'success' => false,
                    'data' => [
                        'message' => $result->getStatus(),
                    ],
                    '_serialize' => ['success', 'data'],
                ]
            );
        }
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        $this->Authentication->logout();
        $this->set(
            [
                'success' => true,
                'data' => true,
                '_serialize' => ['success', 'data'],
            ]
        );
    }

    /**
     * Get activity stream
     *
     * @return void
     */
    public function stream()
    {
        $userId = $this->request->getParam('user_id');
        $identity = $this->Authentication->getIdentity();
        if ($identity == null || !$identity->getIdentifier() == $userId) {
            throw new ForbiddenException();
        }

        $page = (int)Hash::get($this->request->getQueryParams(), 'page', 1);
        $rowsForPage = 10;
        /** @var int $offset */
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('user', $userId, false, $offset, $rowsForPage);
        $this->set([
            'stream' => $stream,
            '_serialize' => 'stream',
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function edit()
    {
        $this->Crud->on('beforeSave', function (Event $event) {
            $hasher = new DefaultPasswordHasher();
            $plain = $event->getSubject()->entity->get('password');
            $event->getSubject()->entity->set('password', $hasher->hash($plain));
        });
        $this->Crud->execute();
    }
}
