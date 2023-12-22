<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\ScoresController as ScoresBaseController;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoresController extends ScoresBaseController
{
    public array $paginate = [
        'limit' => 40,
        'maxLimit' => 40,
    ];

    /**
     * {@inheritDoc}
     *
     * @throws \Crud\Error\Exception\MissingActionException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('last', 'Crud.View');
        $this->Crud->mapAction('viewByMatchday', 'Crud.View');
    }

    /**
     * Last
     *
     * @return void
     * @throws \Exception
     */
    public function last(): void
    {
        $this->viewByMatchday();
    }

    /**
     * View by matchday
     *
     * @param int $matchdayId Matchday id
     * @return void
     * @throws \Exception
     */
    public function viewByMatchday(?int $matchdayId = null): void
    {
        $conditions = [
            'team_id' => (int)$this->getRequest()->getParam('team_id'),
        ];
        if ($matchdayId) {
            $conditions['matchday_id'] = $matchdayId;
        }
        $this->Crud->on('beforeFind', function (EventInterface $event) use ($conditions): SelectQuery {
            /** @var \Cake\ORM\Query\SelectQuery $q */
            $q = $event->getSubject()->query;

            return $q
                ->where($conditions, [], true)
                ->orderBy(['matchday_id' => 'desc']);
        });

        $this->view(null);
    }

    /**
     * Index
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function index(): ResponseInterface
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod([
            'byTeam' => [
                'team_id' => (int)$this->request->getParam('team_id'),
            ],
        ]);

        return $this->Crud->execute();
    }
}
