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
        $this->Auth->allow(['upload']);
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('upload', 'Crud.Edit');
    }

    public function view($id)
    {
        $this->Crud->on(
            'beforeFind',
            function (Event $event) {
                $event->getSubject()->query->contain(['Users', 'EmailSubscriptions']);
            }
        );

        return $this->Crud->execute();
    }

    public function edit($id)
    {
        if ($this->Teams->find()->where(['user_id' => $this->Auth->user('id'), 'id' => $id])->isEmpty()) {
            return new UnauthorizedException('Access denied');
        }
        $this->Crud->action()->saveOptions([
            'associated' => [
                'EmailSubscriptions' => [
                    'accessibleFields' => ['id' => true]
                ]
            ]
        ]);

        return $this->Crud->execute();
    }
}
