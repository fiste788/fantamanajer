<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

class ClubsController extends AppController
{
	public function initialize() {
        parent::initialize();
        $this->Auth->allow(['index','view']);
    }
    
    public function view($id)
    {
        $this->Crud->on('beforeFind', function(\Cake\Event\Event $event) {
            $event->getSubject()->query->contain(['Members' => ['Players','Clubs']]);
        });

        return $this->Crud->execute();
    }
}