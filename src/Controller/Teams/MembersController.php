<?php

namespace App\Controller\Teams;

use Cake\Event\EventInterface;
use App\Controller\MembersController as AppMembersController;

class MembersController extends AppMembersController
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

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('notMine', 'Crud.Index');
    }
}
