<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use const DS;

class PlayersController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['view']);
    }
    
    public function view($id)
    {
        $this->Crud->on('beforeFind', function(Event $event) {
            $event->getSubject()->query->contain(['Members' => function ($q) {
                return $q
                    ->contain(['Roles', 'Clubs', 'Seasons', 'Ratings' => function ($q2) {
                       return $q2->contain(['Matchdays'])
                               ->order(['Matchdays.number' => 'ASC']); 
                    }])
                    ->where(['Members.season_id' => $this->currentSeason->id]);
            }]);
        });
        
        $this->Crud->on('afterFind', function(Event $event) {
            $entity = $event->getSubject()->entity;
            foreach ($entity->members as $member) {
                $this->log($member->club_id, \Psr\Log\LogLevel::NOTICE);
                if(file_exists(Configure::read('App.imagesPath.clubs') . 'bg' . DS . $member->club_id . '.jpg')) {
                    $event->getSubject()->entity->members[0]->backgroundImg = Router::url('/img/clubs/bg/' . $member->club_id . '.jpg', true);
                }
                if(file_exists(Configure::read('App.imagesPath.players') . 'season-' . $member->season->id . DS . $member->code_gazzetta . '.jpg')) {
                    $event->getSubject()->entity->members[0]->img = Router::url('/img/players/season-' . $member->season->id . '/' . $member->code_gazzetta . '.jpg', true);
                }
            }
            
        });

        return $this->Crud->execute();
    }
}