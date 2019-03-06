<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\View\Helper\UrlHelper;

/**
 *
 * @property \App\Model\Table\TeamsTable $Teams
 * @property UrlHelper $Url
 */
class TeamsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('add', 'Crud.Add');
        $this->Crud->mapAction('upload', 'Crud.Edit');
    }

    public function view($id)
    {
        $this->Crud->on(
            'beforeFind',
            function (Event $event) {
                $event->getSubject()->query->contain(['Users', 'PushNotificationSubscriptions', 'EmailNotificationSubscriptions']);
            }
        );

        return $this->Crud->execute();
    }

    public function add()
    {
        $this->Crud->action()->saveOptions(['accessibleFields' => ['user' => true]]);
        $this->Crud->action()->saveMethod('saveWithoutUser');

        return $this->Crud->execute();
    }
}
