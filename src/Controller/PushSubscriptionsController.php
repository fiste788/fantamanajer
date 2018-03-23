<?php
namespace App\Controller;

use App\Model\Table\PushSubscriptionsTable;

/**
 *
 * @property \App\Model\Table\PushSubscriptionsTable $PushSubscriptions
 */
class PushSubscriptionsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('delete', 'Crud.Delete');
    }

    public function isAuthorized($user = null)
    {
        if ($this->request->getData('user_id') == $user['id']) {
            return true;
        }
        parent::isAuthorized($user);
    }
}
