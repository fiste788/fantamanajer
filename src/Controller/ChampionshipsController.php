<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\ChampionshipsTable $Championships
 */
class ChampionshipsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
    }

    public function edit()
    {
        $this->Crud->action()->saveOptions(['associated' => []]);
        $this->Crud->execute();
    }
}
