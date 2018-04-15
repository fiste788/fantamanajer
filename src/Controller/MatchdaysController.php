<?php
namespace App\Controller;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class MatchdaysController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        $this->Crud->mapAction('current', 'Crud.Index');
    }

    public function current()
    {
        $this->set(
            [
            'success' => true,
            'data' => $this->Matchdays->findCurrent(),
            '_serialize' => ['success', 'data']
            ]
        );
    }
}
