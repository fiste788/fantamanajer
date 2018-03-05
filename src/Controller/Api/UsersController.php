<?php

namespace App\Controller\Api;

use Cake\Network\Exception\UnauthorizedException;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    /**
     * Initialize
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add', 'token']);
    }
    
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);

        $this->Crud->mapAction('edit', 'Crud.Edit');
    }
    
    public function isAuthorized($user = null)
    {
        if(!$this->request->getParam('id')) {
            return true;
        }
        if ($this->request->getParam('id') == $user['id']) {
            return true;
        }
        parent::isAuthorized($user);
    }

    /**
     * Get login Token
     *
     * @throws UnauthorizedException
     */
    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Invalid username or password');
        } else {
            $this->Auth->setUser($user);
        }
        $days = $this->request->getData('remember_me', false) ? 365 : 7;

        $this->set(
            [
                'success' => true,
                'data' => [
                    'token' => $this->Users->getToken($user['id'], $days),
                    'user' => $user
                ],
                '_serialize' => ['success', 'data']
            ]
        );
    }

    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }
}
