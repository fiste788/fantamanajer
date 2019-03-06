<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * @property \App\Model\Table\ChampionshipsTable $Championships
 */
class ChampionshipsController extends AppController
{
    public function beforeFilter(Event $event)
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
