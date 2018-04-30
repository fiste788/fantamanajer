<?php

namespace App\Controller\Teams;

use App\Model\Entity\Lineup;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class LineupsController extends \App\Controller\LineupsController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('current', 'Crud.View');
    }

    public function current()
    {
        $team = $this->request->getParam('team_id');

        if ($this->Authentication->getIdentity()->hasTeam($team)) {
            $this->Crud->on('beforeFind', function (Event $event) use ($team) {
                $event->getSubject()->query = $this->Lineups->find('last', [
                    'matchday' => $this->currentMatchday,
                    'team_id' => $team,
                    'contain' => ['Teams' => ['Members' => ['Roles', 'Players']]]
                ]);
            });
        } else {
            $matchdayId = TableRegistry::getTableLocator()->get('Matchdays')->find()
                ->innerJoinWith('Scores')
                ->orderAsc('Matchdays.number')
                ->where([
                    'Scores.id' => null,
                    'season_id' => $this->currentSeason->id
                ])->first();
            $this->Crud->on('beforeFind', function (Event $event) use ($team, $matchdayId) {
                $event->getSubject()->query = $this->Lineups->find('byMatchdayIdAndTeamId', [
                    'matchday_id' => $matchdayId,
                    'team_id' => $team
                ]);
            });
        }
        $this->Crud->on('afterFind', function (Event $event) use ($team) {
            if ($event->getSubject()->entity->team_id == $team) {
                $event->getSubject()->entity = $event->getSubject()->entity->copy($this->currentMatchday, true, false);
            }
            $event->getSubject()->entity->modules = Lineup::$module;
        });

        try {
            return $this->Crud->execute();
        } catch (\Exception $e) {
            $this->set([
                'success' => true,
                'data' => null,
                '_serialize' => ['success', 'data']
            ]);
        }
    }
}
