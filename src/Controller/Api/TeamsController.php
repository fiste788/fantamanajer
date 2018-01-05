<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\Routing\Router;
use Cake\View\Helper\UrlHelper;

/**
 *
 * @property \App\Model\Table\TeamsTable $Teams
 * @property UrlHelper $Url
 */
class TeamsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['upload']);
        $this->Crud->mapAction('upload', 'Crud.Edit');
    }

    public function view($id)
    {
        $this->Crud->on(
            'beforeFind',
            function (Event $event) {
                $event->getSubject()->query->contain(
                    ['Users', 'Members' => function (Query $q) {
                        return $q->contain(['Roles', 'Players', 'Clubs'])
                            ->find('withStats', ['season_id' => $this->currentSeason->id]);
                    }
                    ]
                );
            }
        );
        /*$this->Crud->on('afterFind', function(Event $event) {
            $team = $event->getSubject()->entity;
            if(file_exists(Configure::read('App.paths.images.teams') . $team->id . '.jpg')) {
                $event->getSubject()->entity->img = Router::url('/img/upload/teams/' . $team->id . '.jpg', true);
            }

        });*/

        return $this->Crud->execute();
    }

    public function index()
    {
        $teams = $this->Teams->find()
            ->contain('Users')
            ->where(['championship_id' => $this->request->getParam('championship_id')]);
        //$folder = new Folder(WWW_ROOT . 'img');
        foreach ($teams as $team) {
            if (file_exists(Configure::read('App.paths.images.teams') . $team->id . '.jpg')) {
                $team->img = Router::url('/img/upload/teams/' . $team->id . '.jpg', true);
            }
        }
        $this->set(
            [
            'success' => true,
            'data' => $teams,
            '_serialize' => ['success', 'data']
            ]
        );
    }

    public function edit($id)
    {
        $this->log("EDIT", \Psr\Log\LogLevel::INFO);
        $this->log(print_r($this->request, 1), \Psr\Log\LogLevel::INFO);
        $this->log(print_r($this->Auth->user(), 1), \Psr\Log\LogLevel::INFO);
        if ($this->Teams->find()->where(['user_id' => $this->Auth->user('id'), 'id' => $id])->isEmpty()) {
            return new UnauthorizedException('Access denied');
        }

        return $this->Crud->execute();
        /*$team = $this->Teams->get($this->request->team_id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $team = $this->Teams->patchEntity($team, $this->request->data);
            if ($this->Teams->save($team)) {
                $this->Flash->success(__('The team has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The team could not be saved. Please, try again.'));
            }
        }
        $users = $this->Teams->Users->find('list', ['limit' => 200]);
        $championships = $this->Teams->Championships->find('list', ['limit' => 200]);
        $members = $this->Teams->Members->find('list', ['limit' => 200]);
        $this->set(compact('team', 'users', 'championships', 'members'));
        $this->set('_serialize', ['team']);*/
    }
}
