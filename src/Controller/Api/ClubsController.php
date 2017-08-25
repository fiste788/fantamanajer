<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;

class ClubsController extends AppController
{
	public function initialize() {
        parent::initialize();
        $this->Auth->allow(['index','view']);
    }
    
    public function view($id)
    {
        $this->Crud->on('beforeFind', function(Event $event) {
            $event->getSubject()->query->contain([
                'Members' => function ($q) {
                    return $q->find('withStats',['season_id' => $this->currentSeason->id])
                        ->contain([
                            'Roles',
                            'Players',
                            'Clubs'
                        ]);
                            //->find('withStats');
                }
            ]);
        });
        $this->Crud->on('afterFind', function(Event $event) {
            $entity = $event->getSubject()->entity;
            if(file_exists(Configure::read('App.imagesPath.clubs') . 'bg' . DS . $entity->id . '.jpg')) {
                $event->getSubject()->entity->backgroundImg = Router::url('/img/clubs/bg/' . $entity->id . '.jpg', true);
            }
            if(file_exists(Configure::read('App.imagesPath.clubs') . $entity->id . '.png')) {
                $event->getSubject()->entity->img = Router::url('/img/clubs/' . $entity->id . '.png', true);
            }
        });

        return $this->Crud->execute();
    }
    
    public function index()
    {
        $clubs = $this->Clubs->findBySeason($this->currentSeason);
        foreach ($clubs as $club) {
            if(file_exists(Configure::read('App.imagesPath.clubs') . 'bg' . DS . $club->id . '.jpg')) {
                $club->backgroundImg = Router::url('/img/clubs/bg/' . $club->id . '.jpg', true);
            }
            if(file_exists(Configure::read('App.imagesPath.clubs') . $club->id . '.png')) {
                $club->img = Router::url('/img/clubs/' . $club->id . '.png', true);
            }
        }
        $this->set([
            'success' => true,
            'data' => $clubs,
            '_serialize' => ['success','data']
        ]);
    }
}