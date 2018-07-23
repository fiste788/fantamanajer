<?php

namespace App\Controller;

use Cake\Network\Exception\UnauthorizedException;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);

        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Authentication->allowUnauthenticated(['login', 'logout']);
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
}
