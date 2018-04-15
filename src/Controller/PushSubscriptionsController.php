<?php
namespace App\Controller;

use Cake\Event\Event;

/**
 *
 * @property \App\Model\Table\PushSubscriptionsTable $PushSubscriptions
 */
class PushSubscriptionsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('delete', 'Crud.Delete');
    }
}
