<?php
namespace App\Controller\Teams;

use Cake\Event\Event;
use Cake\Event\EventInterface;

class ScoresController extends \App\Controller\ScoresController
{
    public $paginate = [
        'limit' => 40,
        'maxLimit' => 40
    ];

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('last', 'Crud.View');
        $this->Crud->mapAction('viewByMatchday', 'Crud.View');
    }

    public function last()
    {
        $this->viewByMatchday();
    }

    public function viewByMatchday($matchdayId = null)
    {
        $conditions = [
            'team_id' => $this->getRequest()->getParam('team_id')
        ];
        if ($matchdayId) {
            $conditions['matchday_id'] = $matchdayId;
        }
        $this->Crud->on('beforeFind', function (EventInterface $event) use ($conditions) {
            return $event->getSubject()->query
                ->where($conditions, [], true)
                ->order(['matchday_id' => 'desc']);
        });

        return $this->view(null);
    }

    public function index()
    {
        $this->Crud->action()->findMethod(['byTeam' => [
            'team_id' => $this->request->getParam('team_id')
        ]]);

        return $this->Crud->execute();
    }
}
