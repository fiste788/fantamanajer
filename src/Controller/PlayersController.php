<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\Query;

/**
 * @property \App\Model\Table\PlayersTable $Players
 */
class PlayersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['view']);
    }

    public function view($id)
    {
        $championship_id = $this->request->getQuery('championship_id', null);
        $this->Crud->on(
            'beforeFind',
            function (Event $event) {
            $event->getSubject()->query->contain(
                ['Members' => function (Query $q) {
                        return $q->find('withDetails');
                    }]
            );
        }
        );

        if ($championship_id) {
            $this->Crud->on(
                'afterFind',
                function (Event $event) use ($championship_id) {
                $team = \Cake\ORM\TableRegistry::get("MembersTeams");
                $entity = $event->getSubject()->entity;
                $event->getSubject()->entity->championship_id = $championship_id;
                foreach ($entity->members as $key => $member) {
                    if ($championship_id != null && $member->season_id == $this->currentSeason->id) {
                        $event->getSubject()->entity->members[$key]->free = $team->find()
                                ->innerJoinWith('Teams')
                                ->where(
                                    [
                                        'member_id' => $member->id,
                                        'championship_id' => $championship_id
                                    ]
                                )->isEmpty();
                    }
                }
            }
            );
        }

        return $this->Crud->execute();
    }
}
