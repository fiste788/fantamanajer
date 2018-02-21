<?php

namespace App\Controller\Api\Teams;

use Cake\ORM\TableRegistry;

class LineupsController extends \App\Controller\Api\LineupsController
{

    public function current()
    {
        $teamsTable = TableRegistry::get('Teams');
        $team = $teamsTable->get(
            $this->request->getParam('team_id'),
            ['contain' => ['Members' => ['Roles', 'Players']]]
        );
        $lineup = $this->Lineups->findLast($this->currentMatchday, $team)->first();
        if ($lineup && $lineup->matchday_id != $this->currentMatchday->id) {
            $lineup = $lineup->copy($this->currentMatchday, true, false);
        }

        $this->set(
            [
                'success' => true,
                'data' => [
                    'members' => $team->members,
                    'lineup' => $lineup,
                    'modules' => \App\Model\Entity\Lineup::$module
                ],
                '_serialize' => ['success', 'data']
            ]
        );
    }
}
