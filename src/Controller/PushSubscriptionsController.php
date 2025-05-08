<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Override;

/**
 * @property \App\Model\Table\PushSubscriptionsTable $PushSubscriptions
 */
class PushSubscriptionsController extends AppController
{
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
