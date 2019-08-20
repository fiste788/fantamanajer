<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\Event;

/**
 *
 * @property \App\Model\Table\SelectionsTable $Selections
 */
class SelectionsController extends AppController
{
    public function add()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                $event->getSubject()->entity->matchday_id = $this->currentMatchday->id;
                $event->getSubject()->entity->active = true;
            }
        );

        $this->Crud->execute();
    }
}
