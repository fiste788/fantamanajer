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
        $this->Crud->on(
            'beforeFind',
            function (Event $event) {
                $event->getSubject()->query->contain(
                    [
                    'Members' => function (Query $q) {
                        return $q->find('withStats', ['season_id' => $this->currentSeason->id])
                                ->contain(
                                    [
                                        'Roles',
                                        'Players',
                                        'Clubs'
                                    ]
                                );
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
