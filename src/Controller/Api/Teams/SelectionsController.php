<?php
namespace App\Controller\Api\Teams;

class SelectionsController extends \App\Controller\Api\SelectionsController
{

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
