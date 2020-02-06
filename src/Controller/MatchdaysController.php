<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class MatchdaysController extends AppController
{
    /**
     * @inheritDoc
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
     */
    public function current()
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
