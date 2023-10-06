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
    public function __construct(private LikelyLineupService $LikelyLineup, private LineupService $Lineup)
    {
    }

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
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
    public function current(): ?ResponseInterface
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
                    stats: true
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
                    team_id: $team
                )->contain(['Matchdays', 'Teams' => ['Members' => ['Roles', 'Players', 'Clubs']]]);
            });
        }
        $this->Crud->on('afterFind', function (Event $event) use ($team, $that): void {
            $event->getSubject()->entity = $that->Lineup->duplicate(
                $event->getSubject()->entity,
                $team,
                $this->currentMatchday
            );
        });

        try {
            return $this->Crud->execute();
        } catch (NotFoundException $e) {
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
     * @throws \LogicException
     */
    public function likely(): void
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
