<?php

namespace App\Controller;

use App\Service\CredentialService;
use App\Service\UserService;
use App\Stream\ActivityManager;
use Authentication\Authenticator\UnauthenticatedException;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;

/**
 * @property UserService $User
 * @property CredentialService $Credential
 */
class UsersController extends AppController
{
    use ServiceAwareTrait;

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('User');
        $this->loadService('Credential');
    }

    /**
     * Before filter
     *
     * @param Event $event Event
     * @return void
     */
    public function beforeFilter(Event $event)
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
            '_serialize' => ['success', 'data']
        ]);
    }

    /**
     * Get login Token
     *
     * @return void
     *
     * @throws UnauthenticatedException
     */
    public function login()
    {
        if ($this->Authentication->getResult()->isValid()) {
            $user = $this->Authentication->getIdentity();
            $days = $this->request->getData('remember_me', false) ? 365 : 7;
            $this->set(
                [
                    'success' => true,
                    'data' => [
                        'token' => $this->User->getToken($user->id, $days),
                        'user' => $user->getOriginalData()
                    ],
                    '_serialize' => ['success', 'data']
                ]
            );
        } else {
            throw new UnauthenticatedException($this->Authentication->getResult()->getStatus());
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
                '_serialize' => ['success', 'data']
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
        if (!$this->Authentication->getIdentity()->id == $userId) {
            throw new ForbiddenException();
        }

        $page = $this->request->getQuery('page', 1);
        $rowsForPage = 10;
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('user', $userId, false, $offset, $rowsForPage);
        $this->set([
            'stream' => $stream,
            '_serialize' => 'stream'
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function edit()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                $hasher = new DefaultPasswordHasher();
                $event->getSubject()->entity->set('password', $hasher->hash($event->getSubject()->entity->get('password')));
            }
        );
        $this->Crud->execute();
    }
}
