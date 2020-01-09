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
     */
    public function edit()
    {
        $this->Crud->action()->saveOptions(['associated' => []]);

        return $this->Crud->execute();
    }
}
