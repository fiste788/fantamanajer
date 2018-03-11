<?php

namespace App\Controller\Teams;

use Cake\Event\Event;

class MembersController extends \App\Controller\MembersController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index']);
    }

    public function index()
    {
        $stats = $this->request->getQuery('stats', true);
        $team_id = $this->request->getParam('team_id', null);
        $this->Crud->on(
            'beforePaginate',
            function (Event $event) use ($team_id, $stats) {
                $event->getSubject()->query
                    ->contain(['Clubs', 'Players'])
                    ->matching('Teams', function(\Cake\ORM\Query $q) use ($team_id) {
                        return $q->where(['Teams.id' => $team_id]);
                    });
                if($stats) {
                    $event->getSubject()->query
                    ->contain(['Roles', 'VwMembersStats']);
                }
            }
        );

        return $this->Crud->execute();
    }
}
