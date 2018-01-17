<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 *
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class LineupsController extends AppController
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
            $lineup = $lineup->copy($this->currentMatchday, true);
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

    public function add()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                $event->getSubject()->entity->set('matchday_id', $this->currentMatchday->id);
                //$event->getSubject()->entity->touch();
            }
        );
        $this->Crud->execute();
    }
}
