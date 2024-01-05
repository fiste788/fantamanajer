<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\MembersController as AppMembersController;
use Cake\Event\EventInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppMembersController
{
    /**
     * Pagination
     *
     * @var array<string, mixed>
     */
    protected array $paginate = [
        'limit' => 1000,
        'maxLimit' => 1000,
    ];

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
            'byTeamId' => [
                'team_id' => (int)$this->request->getParam('team_id', null),
                'stats' => $this->request->getQuery('stats', true),
            ],
        ]);

        return $this->Crud->execute();
    }

    /**
     * Not mine
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function notMine(): ResponseInterface
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod([
            'notMine' => [
                'team_id' => (int)$this->request->getParam('team_id', null),
                'role_id' => (int)$this->request->getParam('role_id', null),
            ],
        ]);

        return $this->Crud->execute();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('notMine', 'Crud.Index');
    }
}
