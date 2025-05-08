<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\Event;
use Cake\Event\EventInterface;
use Override;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class LineupsController extends AppController
{
    /**
     * {@inheritDoc}
     *
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     */
    #[Override]
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
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function add(): ResponseInterface
    {
        /** @var \Crud\Action\EditAction $action */
        $action = $this->Crud->action();
        $action->saveOptions(['associated' => ['Dispositions']]);
        $this->Crud->on('beforeSave', function (Event $event): void {
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
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function edit(): ResponseInterface
    {
        /** @var \Crud\Action\EditAction $action */
        $action = $this->Crud->action();
        $action->saveOptions(['associated' => ['Dispositions']]);
        $this->Crud->on('beforeSave', function (Event $event): void {
            /** @var \App\Model\Entity\Lineup $lineup */
            $lineup = $event->getSubject()->entity;
            $lineup->matchday_id = $this->currentMatchday->id;
        });

        return $this->Crud->execute();
    }
}
