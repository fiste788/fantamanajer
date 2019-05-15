<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Event\EventInterface;

/**
 *
 * @property \App\Model\Table\PushSubscriptionsTable $PushSubscriptions
 */
class PushSubscriptionsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('delete', 'Crud.Delete');
    }
}
