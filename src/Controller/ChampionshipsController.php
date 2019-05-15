<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
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
