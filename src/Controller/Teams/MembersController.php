<?php

namespace App\Controller\Teams;

class MembersController extends \App\Controller\MembersController
{
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
}
