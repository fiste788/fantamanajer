<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use App\Model\Table\TeamsTable;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\View\Helper\UrlHelper;
use Psr\Log\LogLevel;

/**
 *
 * @property TeamsTable $Teams
 * @property UrlHelper $Url
 */
class TeamsController extends AppController
{
	public function view($id)
    {
        $this->Crud->on('beforeFind', function(Event $event) {
            $event->getSubject()->query->contain(['Users','Members' => function($q) {
                return $q->contain(['Roles','Players','Clubs'])
                    ->find('withStats');
                }
            ]);
        });
        $this->Crud->on('afterFind', function(\Cake\Event\Event $event) {
            $team = $event->getSubject()->entity;
            if(file_exists(Configure::read('App.imagesPath.teams') . $team->id . '.jpg')) {
                $event->getSubject()->entity->img = Router::url('/img/upload/teams/' . $team->id . '.jpg', true);
            }
            
        });

        return $this->Crud->execute();
    }

    public function index()
    {
        $teams = $this->Teams->findByChampionshipId($this->request->getParam('championship_id'));
        //$folder = new Folder(WWW_ROOT . 'img');
        foreach ($teams as $team) {
            if(file_exists(Configure::read('App.imagesPath.teams') . $team->id . '.jpg')) {
                $team->img = Router::url('/img/upload/teams/' . $team->id . '.jpg', true);
            }
        }
        $this->set([
            'success' => true,
            'data' => $teams,
            '_serialize' => ['success','data']
        ]);
    }
}
