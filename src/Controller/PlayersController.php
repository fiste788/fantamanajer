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
     */
    public function view($id)
    {
        $this->Crud->action()->findMethod(['withDetails' => [
            'championship_id' => $this->request->getQuery('championship_id', null),
        ]]);

        return $this->Crud->execute();
    }
}
