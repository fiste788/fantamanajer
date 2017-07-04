<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * 
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamsController extends AppController
{
	public function view($id)
    {
        $this->Crud->on('beforeFind', function(\Cake\Event\Event $event) {
            $event->getSubject()->query->contain(['Users','Members' => ['Players','Clubs']]);
        });

        return $this->Crud->execute();
    }
    
    public function index()
    {
        $teams = $this->Teams->findByChampionshipId($this->request->getParam('championship_id'));
        $this->set([
            'success' => true,
            'data' => $teams,
            '_serialize' => ['success','data']
        ]);
    }
}