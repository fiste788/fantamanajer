<?php
declare(strict_types=1);

namespace App\Controller\Championships;

use App\Controller\AppController;
use Authorization\Exception\ForbiddenException;
use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppController
{
    public $paginate = [
        'limit' => 1000,
        'maxLimit' => 1000,
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
     * @inheritDoc
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     * @throws \Authorization\Exception\ForbiddenException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('free', 'Crud.Index');
        $this->Crud->mapAction('freeByRole', 'Crud.Index');
        $championshipId = (int)$this->request->getParam('championship_id');
        /** @var \App\Model\Entity\User $identity */
        $identity = $this->Authentication->getIdentity();
        if (!$identity->isInChampionship($championshipId)) {
            throw new ForbiddenException();
        }
    }

    /**
     * Free by role
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function freeByRole()
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod([
            'free' => [
                'championship_id' => (int)$this->request->getParam('championship_id'),
                'stats' => (bool)$this->request->getQuery('stats', true),
                'role' => (int)$this->request->getParam('role_id', null),
            ],
        ]);

        return $this->Crud->execute();
    }

    /**
     * Free
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function free()
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod([
            'free' => [
                'championship_id' => (int)$this->request->getParam('championship_id'),
                'stats' => false,
            ],
        ]);

        $this->Crud->on('afterPaginate', function (EventInterface $event) {
            $collection = new \Cake\Collection\Collection($event->getSubject()->entities);
            $event->getSubject()->entities = $collection->groupBy('role_id')->toArray();
        });

        return $this->Crud->execute();
    }
}
