<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

class MembersController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['index']);
    }
    
    public function index()
    {
        $this->Crud->on('startup', function(\Cake\Event\Event $event) {
            $event->getSubject()->query->contain(['Clubs','Seasons','Players']);
        });

        return $this->Crud->execute();
        
    }
}