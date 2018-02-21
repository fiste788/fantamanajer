<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
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
        $this->Crud->mapAction('upload', 'Crud.Edit');
    }

    public function view($id)
    {
        $this->Crud->on(
            'beforeFind',
            function (Event $event) {
                $event->getSubject()->query->contain(['Users']);
            }
        );

        return $this->Crud->execute();
    }

    public function edit($id)
    {
        if ($this->Teams->find()->where(['user_id' => $this->Auth->user('id'), 'id' => $id])->isEmpty()) {
            return new UnauthorizedException('Access denied');
        }

        return $this->Crud->execute();
    }
}
