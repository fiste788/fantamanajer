<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

class PlayersController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['view']);
    }
    
    public function view($id)
    {
        $this->Crud->on('beforeFind', function(\Cake\Event\Event $event) {
            $event->getSubject()->query->contain(['Members' => function ($q) {
                return $q
                    ->contain(['Clubs','Seasons','Ratings' => function ($q2) {
                       return $q2->contain(['Matchdays'])
                               ->order(['Matchdays.number' => 'ASC']); 
                    }])
                    ->where(['Members.season_id' => $this->currentSeason->id]);
            }]);
        });

        return $this->Crud->execute();
    }
}