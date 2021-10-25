<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\LineupsController as ControllerLineupsController;
use Cake\Event\Event;
use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Service\LineupService $Lineup
 * @property \App\Service\LikelyLineupService $LikelyLineup
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class LineupsController extends ControllerLineupsController
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
     * @return \Psr\Http\Message\ResponseInterface|null
     * @throws \Exception
     */
    public function current()
    {
        $team = (int)$this->request->getParam('team_id');
        $that = $this;
        /** @var \App\Model\Entity\User $identity */
        $identity = $this->Authentication->getIdentity();
        if ($identity->hasTeam($team)) {
            /*$this->Crud->action()->findMethod(['last' => [
                'team_id' => $team,
                'matchday' => $this->currentMatchday,
                'stats' => true,
            ]]);*/
            $this->Crud->on('beforeFind', function (Event $event) use ($team, $that) {
                $event->getSubject()->query = $that->Lineups->find('last', [
                    'team_id' => $team,
                    'matchday' => $this->currentMatchday,
                    'stats' => true,
                ]);
            });
        } else {
            /** @var \App\Model\Entity\Matchday $matchday */
            $matchday = $this->Matchdays->find('firstWithoutScores', [
                'season' => $this->currentSeason->id,
            ])->first();

            $this->Crud->on('beforeFind', function (Event $event) use ($team, $matchday, $that) {
                $event->getSubject()->query = $that->Lineups->find('byMatchdayIdAndTeamId', [
                    'matchday_id' => $matchday->id,
                    'team_id' => $team,
                ])->contain(['Matchdays', 'Teams' => ['Members' => ['Roles', 'Players', 'Clubs']]]);
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
            $lineup = $this->Lineup->getEmptyLineup($team);
            $lineup->matchday = $this->currentMatchday;
            $this->set([
                'success' => true,
                'data' => $lineup,
                '_serialize' => ['success', 'data'],
            ]);
        }

        return null;
    }

    /**
     * Likely
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    public function likely()
    {
        $teamId = (int)$this->request->getParam('team_id');
        $team = $this->LikelyLineup->get($teamId);
        $this->set([
            'success' => true,
            'data' => $team->members,
            '_serialize' => ['success', 'data'],
        ]);
    }
}
