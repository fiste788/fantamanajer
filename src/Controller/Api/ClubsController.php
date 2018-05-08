<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Event\Event;
use Cake\ORM\Query;

/**
 * @property \App\Model\Table\ClubsTable $Clubs
 */
class ClubsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index', 'view']);
    }

    public function view($id)
    {
        $seasonId = $this->currentSeason->id;
        $this->Crud->on(
            'beforeFind',
            function (Event $event) use ($seasonId) {
                $event->getSubject()->query->contain(
                    [
                        'Members' => function ($q) use ($seasonId) {
                            return $q->contain([
                                'Roles',
                                'Players',
                                'Clubs',
                                'VwMembersStats'
                            ])->where(['season_id' => $seasonId]);
                            ;
                        }
                    ]
                );
            }
        );

        return $this->Crud->execute();
    }

    public function index()
    {
        $clubs = $this->Clubs->findBySeason($this->currentSeason);
        $this->set(
            [
                'success' => true,
                'data' => $clubs,
                '_serialize' => ['success', 'data']
            ]
        );
    }
}
