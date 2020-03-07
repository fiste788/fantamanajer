<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\PlayersTable $Players
 */
class PlayersController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['view']);
    }

    /**
     * Index
     *
     * @param int $id Id
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function view($id)
    {
        /** @var \Crud\Action\ViewAction $action */
        $action = $this->Crud->action();
        $action->findMethod(['withDetails' => [
            'championship_id' => $this->request->getQuery('championship_id', null),
        ]]);

        return $this->Crud->execute();
    }
}
