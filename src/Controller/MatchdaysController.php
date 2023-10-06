<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\View\JsonView;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class MatchdaysController extends AppController
{
    /**
     * @inheritDoc
     */
    public function viewClasses(): array
    {
        return [JsonView::class];
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
        $this->Crud->mapAction('current', 'Crud.View');
        $this->Authentication->allowUnauthenticated(['current']);
        $this->Authorization->skipAuthorization();
    }

    /**
     * Current
     *
     * @return void
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     * @throws \RuntimeException
     */
    public function current(): void
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod(['current']);
        $this->withMatchdayCache();

        $this->set([
            'data' => $this->currentMatchday,
            'success' => true,
            '_serialize' => ['data', 'success'],
        ]);
    }
}
