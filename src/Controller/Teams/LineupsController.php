<?php

namespace App\Controller\Teams;

use App\Model\Entity\Lineup;
use Cake\Event\Event;

/**
 *
 * @property \App\Model\Table\LineupsTable $Lineups
 * @property \App\Service\LineupService $Lineup
 * @property \App\Service\LikelyLineupService $LikelyLineup
 */
class LineupsController extends \App\Controller\LineupsController
{

    public function initialize() : void
    {
        parent::initialize();
        $this->loadService('LikelyLineup');
        $this->loadService('Lineup');
        $this->loadModel('Matchdays');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('current', 'Crud.View');
        $this->Crud->mapAction('likely', 'Crud.View');
    }

    public function current()
    {
        $team = $this->request->getParam('team_id');
        $that = $this;
        if ($this->Authentication->getIdentity()->hasTeam($team)) {
            $this->Crud->on('beforeFind', function (Event $event) use ($team, $that) {
                $event->getSubject()->query = $that->Lineups->find('last', [
                    'team_id' => $team,
                    'matchday' => $this->currentMatchday,
                    'stats' => true
                ]);
            });
        } else {
            $matchdayId = $this->Matchdays->find('firstWithoutScores')->first();
            $this->Crud->on('beforeFind', function (Event $event) use ($team, $matchdayId) {
                $event->getSubject()->query = $this->Crud->findMethod(['byMatchdayIdAndTeamId' => [
                    'matchday_id' => $matchdayId,
                    'team_id' => $team,
                    'contain' => ['Teams' => ['Members' => ['Roles', 'Players']]]
                ]]);
            });
        }
        $this->Crud->on('afterFind', function (Event $event) use ($team, $that) {
            $event->getSubject()->entity = $that->Lineup->duplicate($event->getSubject()->entity, $team, $this->currentMatchday);
        });

        try {
            return $this->Crud->execute();
        } catch (\Cake\Http\Exception\NotFoundException $e) {
            $this->set([
                'success' => true,
                'data' => $this->Lineup->getEmptyLineup($team),
                '_serialize' => ['success', 'data']
            ]);
        }
    }

    public function likely()
    {
        $teamId = $this->request->getParam('team_id');
        $team = $this->LikelyLineup->get($teamId);
        $this->set([
            'success' => true,
            'data' => $team->members,
            '_serialize' => ['success', 'data']
        ]);
    }
}
