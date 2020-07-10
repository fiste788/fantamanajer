<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use Cake\Event\EventInterface;
use Cake\ORM\Query;

/**
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoresController extends \App\Controller\ScoresController
{
    public $paginate = [
        'limit' => 40,
        'maxLimit' => 40,
    ];

    /**
     * Undocumented function
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

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
    public function last()
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
    public function viewByMatchday($matchdayId = null)
    {
        $conditions = [
            'team_id' => (int)$this->getRequest()->getParam('team_id'),
        ];
        if ($matchdayId) {
            $conditions['matchday_id'] = $matchdayId;
        }
        $this->Crud->on('beforeFind', function (EventInterface $event) use ($conditions): Query {
            /** @var \Cake\ORM\Query $q */
            $q = $event->getSubject()->query;

            return $q
                ->where($conditions, [], true)
                ->order(['matchday_id' => 'desc']);
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
    public function index()
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod(['byTeam' => [
            'team_id' => (int)$this->request->getParam('team_id'),
        ]]);

        return $this->Crud->execute();
    }
}
