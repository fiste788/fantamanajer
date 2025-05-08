<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Override;

/**
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
{
    /**
     * Pagination
     *
     * @var array<string, mixed>
     */
    protected array $paginate = [
        'page' => 1,
        'limit' => 5,
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
    #[Override]
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     */
    #[Override]
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('delete', 'Crud.Delete');
    }
}
