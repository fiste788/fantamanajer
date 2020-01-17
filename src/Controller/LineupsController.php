<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\Event;
use Cake\Event\EventInterface;

/**
 *
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class LineupsController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('add', 'Crud.Add');
    }

    /**
     * Add
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function add()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                $event->getSubject()->entity->set('team', null);
                $event->getSubject()->entity->set('matchday_id', $this->currentMatchday->id);
            }
        );

        return $this->Crud->execute();
    }

    /**
     * Edit
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function edit()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                $event->getSubject()->entity->set('team', null);
                $event->getSubject()->entity->set('matchday_id', $this->currentMatchday->id);
            }
        );

        return $this->Crud->execute();
    }
}
