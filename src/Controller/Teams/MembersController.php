<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\MembersController as AppMembersController;
use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppMembersController
{
    public $paginate = [
        'limit' => 1000,
        'maxLimit' => 1000,
    ];

    /**
     * Index
     *
     * @return \Cake\Http\Response
     */
    public function index()
    {
        $this->Crud->action()->findMethod([
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
     * @return \Cake\Http\Response
     */
    public function notMine()
    {
        $this->Crud->action()->findMethod([
            'notMine' => [
                'team_id' => (int)$this->request->getParam('team_id', null),
                'role_id' => (int)$this->request->getParam('role_id', null),
            ],
        ]);

        return $this->Crud->execute();
    }

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('notMine', 'Crud.Index');
    }
}
