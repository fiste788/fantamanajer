<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
{
    public $paginate = [
        'page' => 1,
        'limit' => 5,
        'maxLimit' => 15,
        'sortWhitelist' => [
            'id', 'title',
        ],
    ];

    /**
     * @inheritDoc
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
}
