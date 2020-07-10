<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\Event;

/**
 * @property \App\Model\Table\SelectionsTable $Selections
 */
class SelectionsController extends AppController
{
    /**
     * add
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function add()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                /** @var \App\Model\Entity\Selection $selection */
                $selection = $event->getSubject()->entity;
                $selection->matchday_id = $this->currentMatchday->id;
                $selection->active = true;
            }
        );

        return $this->Crud->execute();
    }
}
