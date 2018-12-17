<?php

namespace App\Controller;

use App\Model\Table\UsersTable;
use App\Stream\ActivityManager;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Network\Exception\UnauthorizedException;

/**
 * @property UsersTable $Users
 */
class UsersController extends AppController
{
    use ServiceAwareTrait;
    
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('User');
    }
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Authentication->allowUnauthenticated(['login']);
    }

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
     * @throws UnauthorizedException
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
                        'token' => $this->Users->getToken($user->id, $days),
                        'user' => $user->getOriginalData()
                    ],
                    '_serialize' => ['success', 'data']
                ]
            );
        } else {
            throw new \Exception($this->Authentication->getResult()->getStatus(), 401);
        }
    }

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
}
