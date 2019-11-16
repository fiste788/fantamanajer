<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use Cake\Event\Event;
use Cake\Event\EventInterface;

/**
 *
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Service\LineupService $Lineup
 * @property \App\Service\LikelyLineupService $LikelyLineup
 */
class LineupsController extends \App\Controller\LineupsController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('LikelyLineup');
        $this->loadService('Lineup');
        $this->loadModel('Matchdays');
    }

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('current', 'Crud.View');
        $this->Crud->mapAction('likely', 'Crud.View');
    }

    /**
     * current
     *
     * @return \Cake\Http\Response
     */
    public function current()
    {
        $team = $this->request->getParam('team_id');
        $that = $this;
        if ($this->Authentication->getIdentity()->hasTeam($team)) {
            $this->Crud->on('beforeFind', function (Event $event) use ($team, $that) {
                $event->getSubject()->query = $that->Lineups->find('last', [
                    'team_id' => $team,
                    'matchday' => $this->currentMatchday,
                    'stats' => true,
                ]);
            });
        } else {
            $matchday = $this->Matchdays->find('firstWithoutScores', [
                'season' => $this->currentSeason->id,
            ])->first();

            $this->Crud->on('beforeFind', function (Event $event) use ($team, $matchday, $that) {
                $event->getSubject()->query = $that->Lineups->find('byMatchdayIdAndTeamId', [
                    'matchday_id' => $matchday->id,
                    'team_id' => $team,
                ])->contain(['Teams' => ['Members' => ['Roles', 'Players']]]);
            });
        }
        $this->Crud->on('afterFind', function (Event $event) use ($team, $that) {
            $event->getSubject()->entity = $that->Lineup->duplicate(
                $event->getSubject()->entity,
                $team,
                $this->currentMatchday
            );
        });

        try {
            return $this->Crud->execute();
        } catch (\Cake\Http\Exception\NotFoundException $e) {
            $this->set([
                'success' => true,
                'data' => $this->Lineup->getEmptyLineup($team),
                '_serialize' => ['success', 'data'],
            ]);
        }
    }

    /**
     * Likely
     *
     * @return void
     */
    public function likely()
    {
        $teamId = $this->request->getParam('team_id');
        $team = $this->LikelyLineup->get($teamId);
        $this->set([
            'success' => true,
            'data' => $team->members,
            '_serialize' => ['success', 'data'],
        ]);
    }
}
