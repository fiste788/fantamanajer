<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\ChampionshipsTable $Championships
 */
class ChampionshipsController extends AppController
{
    /**
     * @inheritDoc
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
    }

    /**
     * Edit
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function edit()
    {
        /** @var \Crud\Action\AddAction $action */
        $action = $this->Crud->action();
        $action->saveOptions(['associated' => []]);

        return $this->Crud->execute();
    }
}
