<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Lineup;
use App\Service\ComputeScoreService;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Psr\Http\Message\ResponseInterface;
use function Cake\Core\toBool;

/**
 * @property \App\Service\LineupService $Lineup
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Service\ComputeScoreService $ComputeScore
 */
class ScoresController extends AppController
{
    /**
     * {@inheritDoc}
     *
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
    }

    /**
     * View
     *
     * @param string|null $id Id
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function view(?string $id): ResponseInterface
    {
        $members = toBool($this->request->getQuery('members')) ?? false;
        $this->Crud->on('afterFind', function (Event $event) use ($members) {
            /** @var \App\Model\Entity\Score $score */
            $score = $event->getSubject()->entity;
            $score = $this->Scores->loadDetails($score, $members);
            if ($members) {
                if ($score->lineup == null) {
                    $score->lineup = $this->Lineup->newLineup($score->team_id, $score->matchday_id);
                }
                $score->lineup->modules = Lineup::$MODULES;
            }

            return $score;
        });

        return $this->Crud->execute();
    }

    /**
     * Edit
     *
     * @param \App\Service\ComputeScoreService $computeScore Compute score service
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function edit(ComputeScoreService $computeScore): ResponseInterface
    {
        /** @var \Crud\Action\EditAction $action */
        $action = $this->Crud->action();
        /** @var \App\Model\Entity\User $user */
        $user = $this->Authentication->getIdentity();
        $action->saveOptions([
            'admin' => $user->admin,
            'accessibleFields' => ['*' => true],
            'associated' => [
                'Lineups' => [
                    'accessibleFields' => ['*' => true],
                    'associated' => ['Dispositions'],
                ],
            ],
        ]);

        $this->Crud->on('afterSave', function (Event $event) use ($computeScore): void {
            /** @var \App\Model\Entity\Score $score */
            $score = $event->getSubject()->entity;
            $computeScore->exec($score);
            $this->Scores->save($score);
        });

        return $this->Crud->execute();
    }
}
