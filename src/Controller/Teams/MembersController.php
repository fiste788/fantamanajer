<?php

namespace App\Controller\Teams;

class MembersController extends \App\Controller\MembersController
{
    public $paginate = [
        'limit' => 1000,
        'maxLimit' => 1000
    ];
    
    public function index()
    {
        $this->Crud->action()->findMethod([
            'byTeamId' => [
                'team_id' => $this->request->getParam('team_id', null),
                'stats' => $this->request->getQuery('stats', true)
            ]
        ]);

        return $this->Crud->execute();
    }
    
    public function notMine()
    {
        $this->Crud->action()->findMethod([
            'notMine' => [
                'team_id' => $this->request->getParam('team_id', null),
                'role_id' => $this->request->getParam('role_id', null)
            ]
        ]);

        return $this->Crud->execute();
    }
    
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('notMine', 'Crud.Index');
    }
}
