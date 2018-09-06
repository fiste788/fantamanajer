<?php
namespace App\Controller\Championships;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppController
{
    public $paginate = [
        'limit' => 1000,
        'maxLimit' => 1000
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('free', 'Crud.Index');
        $this->Crud->mapAction('freeByRole', 'Crud.Index');
        $championshipId = $this->request->getParam('championship_id');
        if (!$this->Authentication->getIdentity()->isInChampionship($championshipId)) {
            throw new \Cake\Http\Exception\ForbiddenException();
        }
    }

    public function freeByRole()
    {
        $this->Crud->action()->findMethod([
            'free' => [
                'championship_id' => $this->request->getParam('championship_id'),
                'stats' => $this->request->getQuery('stats', true),
                'role' => $this->request->getParam('role_id', null)
            ]
        ]);

        return $this->Crud->execute();
    }

    public function free()
    {
        $this->Crud->action()->findMethod([
            'free' => [
                'championship_id' => $this->request->getParam('championship_id'),
                'stats' => false,
            ]
        ]);

        $this->Crud->on('afterPaginate', function (Event $event) {
            $collection = new \Cake\Collection\Collection($event->getSubject()->entities);
            $event->getSubject()->entities = $collection->groupBy('role_id')->toArray();
        });

        return $this->Crud->execute();
    }
}
