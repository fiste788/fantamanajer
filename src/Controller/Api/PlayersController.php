<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\Routing\Router;
use const DS;

/**
 * @property PlayersTable $Players
 */
class PlayersController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['view']);
    }
    
    public function view($id)
    {
        $championship_id = $this->request->getQuery('championship_id',null);
        $this->Crud->on('beforeFind', function(Event $event) {
            $event->getSubject()->query->contain(['Members' => function (Query $q) {
                return $q
                    ->contain(['Roles', 'Clubs', 'Seasons', 'Ratings' => function (Query $q2) {
                       return $q2->contain(['Matchdays'])
                               ->order(['Matchdays.number' => 'ASC']); 
                    }])
                    ->order(['Seasons.year' => 'DESC']);
                    //->where(['Members.season_id' => $this->currentSeason->id]);
            }]);
        });
        
        $this->Crud->on('afterFind', function(Event $event) use ($championship_id) {
            $entity = $event->getSubject()->entity;
            $event->getSubject()->entity->championship_id = $championship_id;
            foreach ($entity->members as $key=> $member) {
                if($championship_id != null && $member->season_id == $this->currentSeason->id) {
                    $team = \Cake\ORM\TableRegistry::get("MembersTeams");
                    $event->getSubject()->entity->members[$key]->free = $team->find()
                            ->innerJoinWith('Teams')
                            ->where([
                                'member_id' => $member->id, 
                                'championship_id' => $championship_id
                            ])->isEmpty();
                }
                if(file_exists(Configure::read('App.imagesPath.clubs') . 'bg' . DS . $member->club_id . '.jpg')) {
                    $event->getSubject()->entity->members[$key]->backgroundImg = Router::url('/img/clubs/bg/' . $member->club_id . '.jpg', true);
                }
                if(file_exists(Configure::read('App.imagesPath.players') . 'season-' . $member->season->id . DS . $member->code_gazzetta . '.jpg')) {
                    $event->getSubject()->entity->members[$key]->img = Router::url('/img/players/season-' . $member->season->id . '/' . $member->code_gazzetta . '.jpg', true);
                }
            }
            
        });

        return $this->Crud->execute();
    }
}