<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 *
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class LineupsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('add', 'Crud.Add');
    }

    public function add()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                $event->getSubject()->entity->set('team', null);
                $event->getSubject()->entity->set('matchday_id', $this->currentMatchday->id);
            }
        );
        $this->Crud->execute();
    }

    public function edit()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                $event->getSubject()->entity->set('team', null);
                $event->getSubject()->entity->set('matchday_id', $this->currentMatchday->id);
            }
        );
        $this->Crud->execute();
    }
}
