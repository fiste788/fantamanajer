<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Lineup;
use Cake\Event\Event;
use Cake\Event\EventInterface;

/**
 *
 * @property \App\Service\LineupService $Lineup
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Service\ComputeScoreService $ComputeScore
 */
class ScoresController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('Lineup');
        $this->loadService('ComputeScore');
    }

    /**
     * @inheritDoc
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
     */
    public function view(?string $id)
    {
        $members = (bool) $this->request->getQuery('members', false);
        $that = $this;
        $this->Crud->on('afterFind', function (Event $event) use ($members, $that) {
            /** @var \App\Model\Entity\Score $score */
            $score = $event->getSubject()->entity;
            $score = $this->Scores->loadDetails($score, $members);
            if ($members) {
                if ($score->lineup == null) {
                    $score->lineup = $that->Lineup->newLineup($score->team_id, $score->matchday_id);
                }
                $score->lineup->modules = Lineup::$modules;
            }

            return $score;
        });

        return $this->Crud->execute();
    }

    /**
     * Edit
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function edit()
    {
        /** @var \Crud\Action\EditAction $action */
        $action = $this->Crud->action();
        $action->saveOptions(['associated' => [
            'Lineups' => [
                'accessibleFields' => ['id' => true],
                'associated' => ['Dispositions'],
            ],
        ]]);

        $this->Crud->on('afterSave', function (Event $event) {
            /** @var \App\Model\Entity\Score $score */
            $score = $event->getSubject()->entity;
            $this->ComputeScore->exec($score);
            $this->Scores->save($score);
        });

        return $this->Crud->execute();
    }
}
