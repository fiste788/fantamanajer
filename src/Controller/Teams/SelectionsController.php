<?php
namespace App\Controller\Teams;

class SelectionsController extends \App\Controller\SelectionsController
{

    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
        
    }
    
    public function index()
    {
        $selections = $this->Selections
            ->findByTeamIdAndMatchdayId($this->request->getParam('team_id'), $this->currentMatchday->id)
            ->contain(['Teams', 'OldMembers.Players', 'NewMembers.Players', 'Matchdays']);
        $this->set(
            [
            'success' => true,
            'data' => $selections->last(),
            '_serialize' => ['success', 'data']
            ]
        );
    }
}
