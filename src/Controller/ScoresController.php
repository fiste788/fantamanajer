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
            $result = $this->Scores->loadDetails($event->getSubject()->entity, $members);
            if ($members) {
                if ($result->lineup == null) {
                    $result->lineup = $that->Lineup->newLineup($result->team_id, $result->matchday_id);
                }
                $result->lineup->modules = Lineup::$modules;
            }

            return $result;
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
        $this->Crud->action()->saveOptions(['associated' => [
            'Lineups' => [
                'accessibleFields' => ['id' => true],
                'associated' => ['Dispositions'],
            ],
        ]]);

        $this->Crud->on('afterSave', function (\Cake\Event\Event $event) {
            $this->ComputeScore->exec($event->getSubject()->entity);
            $this->Scores->save($event->getSubject()->entity);
        });

        return $this->Crud->execute();
    }
}
