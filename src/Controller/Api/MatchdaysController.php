<?php
namespace App\Controller\Api;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class MatchdaysController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow('current');
    }

    public function beforeFilter(\Cake\Event\Event $event)
    {
        $this->Crud->mapAction('current', [
            'className' => 'Crud.Index'
        ]);
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
