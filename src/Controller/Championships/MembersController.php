<?php
declare(strict_types=1);

namespace App\Controller\Championships;

use App\Controller\AppController;
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
     * @inheritDoc
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
            throw new \Cake\Http\Exception\ForbiddenException();
        }
    }

    /**
     * Free by role
     *
     * @return \Cake\Http\Response
     */
    public function freeByRole()
    {
        $this->Crud->action()->findMethod([
            'free' => [
                'championship_id' => $this->request->getParam('championship_id'),
                'stats' => $this->request->getQuery('stats', true),
                'role' => $this->request->getParam('role_id', null),
            ],
        ]);

        return $this->Crud->execute();
    }

    /**
     * Free
     *
     * @return \Cake\Http\Response
     */
    public function free()
    {
        $this->Crud->action()->findMethod([
            'free' => [
                'championship_id' => $this->request->getParam('championship_id'),
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
