<?php
namespace App\Controller\Api;

use Cake\Event\Event;

class MatchdaysController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow('current');
    }

    public function beforeFilter(\Cake\Event\Event $event)
    {
        $this->Crud->mapAction(
            'current',
            [
            'className' => 'Crud.Index'
            ]
        );
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
