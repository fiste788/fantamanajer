<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use App\Model\Table\MembersTable;
use Cake\Event\Event;

/**
 * @property MembersTable $Members
 */
class MembersController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['index']);
    }
    
    public function index()
    {
        $this->Crud->on('startup', function(Event $event) {
            $event->getSubject()->query->contain(['Clubs','Seasons','Players']);
        });

        return $this->Crud->execute();
        
    }
    
    public function free()
    {
        $defaultRole = $this->request->getParam('role_id', null);
        $championshipId = $this->request->getParam('championship_id');
        $members = $this->Members->findFree($championshipId);
        if(!is_null($defaultRole)) {
            $members->where(['role_id' => $defaultRole]);
        }
        
        $this->set([
            'success' => true,
            'data' => $members,
            '_serialize' => ['success','data']
        ]);
    }
}