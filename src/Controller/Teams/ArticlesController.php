<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
{
    public array $paginate = [
        'page' => 1,
        'limit' => 1,
        'maxLimit' => 15,
        'sortWhitelist' => [
            'id',
            'title',
        ],
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
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('delete', 'Crud.Delete');
    }

    /**
     * Index
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function index(): ResponseInterface
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod([
            'byTeamId' => ['teamId' => (int) $this->request->getParam('team_id')],
        ]);

        return $this->Crud->execute();
    }
}