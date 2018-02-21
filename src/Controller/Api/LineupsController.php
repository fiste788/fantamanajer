<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Event\Event;

/**
 *
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class LineupsController extends AppController
{

    public function add()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                $event->getSubject()->entity->set('matchday_id', $this->currentMatchday->id);
            }
        );
        $this->Crud->execute();
    }
}
