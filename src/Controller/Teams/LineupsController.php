<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\LineupsController as ControllerLineupsController;
use App\Service\LikelyLineupService;
use App\Service\LineupService;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class LineupsController extends ControllerLineupsController
{
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
    public function current(LineupService $lineupService): ?ResponseInterface
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
            $this->Crud->on('beforeFind', function (Event $event) use ($team, $that): void {
                $event->getSubject()->query = $that->Lineups->find(
                    'last',
                    team_id: $team,
                    matchday: $this->currentMatchday,
                    stats: true,
                );
            });
        } else {
            $matchdaysTable = $this->fetchTable('Matchdays');
            /** @var \App\Model\Entity\Matchday $matchday */
            $matchday = $matchdaysTable->find('firstWithoutScores', season: $this->currentSeason->id)->first();

            $this->Crud->on('beforeFind', function (Event $event) use ($team, $matchday, $that): void {
                $event->getSubject()->query = $that->Lineups->find(
                    'byMatchdayIdAndTeamId',
                    matchday_id: $matchday->id,
                    team_id: $team,
                )->contain(['Matchdays', 'Teams' => ['Members' => ['Roles', 'Players', 'Clubs']]]);
            });
        }
        $this->Crud->on('afterFind', function (Event $event) use ($team, $lineupService): void {

            $event->getSubject()->entity = $lineupService->duplicate(
                $event->getSubject()->entity,
                $team,
                $this->currentMatchday,
            );
        });

        try {
            return $this->Crud->execute();
        } catch (NotFoundException $e) {
            $lineup = $lineupService->getEmptyLineup($team);
            $lineup->matchday = $this->currentMatchday;
            $this->set([
                'success' => true,
                'data' => $lineup,
            ]);

            $this->viewBuilder()->setOption('serialize', ['data', 'success']);
        }

        return null;
    }

    /**
     * Likely
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function likely(LikelyLineupService $likelyLineupService): void
    {
        $teamId = (int)$this->request->getParam('team_id');
        $team = $likelyLineupService->get($teamId);
        $this->set([
            'success' => true,
            'data' => $team->members,
        ]);

        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }
}
