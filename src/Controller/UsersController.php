<?php
declare(strict_types=1);

namespace App\Controller;

use App\Stream\ActivityManager;
use Authentication\Authenticator\UnauthenticatedException;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Log\Log;

/**
 * @property \App\Service\UserService $User
 * @property \App\Service\CredentialService $Credential
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
     * @param \Cake\Event\Event $event Event
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
     * @throws \Cake\Network\Exception\UnauthorizedException
     */
    public function login()
    {
        Log::error('test');
        if ($this->Authentication->getResult()->isValid()) {
            Log::error('valido');
            $user = $this->Authentication->getIdentity();
            $days = $this->request->getData('remember_me', false) ? 365 : 7;
            $this->set(
                [
                    'success' => true,
                    'data' => [
                        'token' => $this->User->getToken($user->id, $days),
                        'user' => $user->getOriginalData(),
                    ],
                    '_serialize' => ['success', 'data'],
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
        if (!$this->Authentication->getIdentity()->id == $userId) {
            throw new ForbiddenException();
        }

        $page = (int)$this->request->getQuery('page', 1);
        $rowsForPage = 10;
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('user', $userId, false, $offset, $rowsForPage);
        $this->set([
            'stream' => $stream,
            '_serialize' => 'stream',
        ]);
    }
}
