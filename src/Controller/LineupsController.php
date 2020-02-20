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
        /** @var \Crud\Action\EditAction $action */
        $action = $this->Crud->action();
        $action->saveOptions(['associated' => ['Dispositions']]);
        $this->Crud->on('beforeSave', function (Event $event) {
            /** @var \App\Model\Entity\Lineup $lineup */
            $lineup = $event->getSubject()->entity;
            $lineup->matchday_id = $this->currentMatchday->id;
        });

        return $this->Crud->execute();
    }

    /**
     * Edit
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function edit()
    {
        /** @var \Crud\Action\EditAction $action */
        $action = $this->Crud->action();
        $action->saveOptions(['associated' => ['Dispositions']]);
        $this->Crud->on('beforeSave', function (Event $event) {
            /** @var \App\Model\Entity\Lineup $lineup */
            $lineup = $event->getSubject()->entity;
            $lineup->matchday_id = $this->currentMatchday->id;
        });

        return $this->Crud->execute();
    }
}
