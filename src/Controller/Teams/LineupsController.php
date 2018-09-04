<?php

namespace App\Controller\Teams;

use App\Model\Entity\Lineup;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 *
 * @property \App\Model\Table\LineupsTable $Lineups
 */
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
                ->select('Matchdays.id')
                ->leftJoinWith('Scores')
                ->orderAsc('Matchdays.number')
                ->whereNull('Scores.id')->andWhere([
                    'Matchdays.number >' => 0,
                    'season_id' => $this->currentSeason->id
                ])->first();
            $this->Crud->on('beforeFind', function (Event $event) use ($team, $matchdayId) {
                $event->getSubject()->query = $this->Lineups->find('byMatchdayIdAndTeamId', [
                    'matchday_id' => $matchdayId->id,
                    'team_id' => $team,
                    'contain' => ['Teams' => ['Members' => ['Roles', 'Players']]]
                ]);
            });
        }
        $this->Crud->on('afterFind', function (Event $event) use ($team) {
            $lineup = $event->getSubject()->entity;
            if ($lineup->team_id == $team && $lineup->matchday_id != $this->currentMatchday->id) {
                $event->getSubject()->entity = $event->getSubject()->entity->copy($this->currentMatchday, true, false);
            }
            $event->getSubject()->entity->modules = Lineup::$module;
        });

        try {
            return $this->Crud->execute();
        } catch (\Cake\Http\Exception\NotFoundException $e) {
            $lineup = new Lineup();
            $lineup->team = TableRegistry::get('Teams')->get($team, ['contain' => ['Members' => ['Roles', 'Players']]]);
            $lineup->modules = Lineup::$module;
            $this->set([
                'success' => true,
                'data' => $lineup,
                '_serialize' => ['success', 'data']
            ]);
        }
    }
}
