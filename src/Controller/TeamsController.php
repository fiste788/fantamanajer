<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\TeamsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Teams Controller
 *
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['view', 'edit']);
        $this->loadModel('Teams');
    }

    /**
     * Index method
     *
     * @return Response|null
     */
    public function index($championshipId)
    {
        $teams = $this->Teams->find(
            'all',
            [
            'conditions' => [
                'championship_id' => $championshipId,
            ],
            'contain' => ['Users']
            ]
        );

        $this->set(compact('teams'));
        $this->set('_serialize', ['teams']);
    }

    /**
     * View method
     *
     * @param  string|null $id Team id.
     * @return Response|null
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $team = $this->Teams->get(
            $id,
            [
            'contain' => ['Users', 'Members' => ['Seasons', 'Players', 'Clubs', 'Roles']]
            ]
        );
        $matchday_id = TableRegistry::get('Scores')->findMatchdayWithPoints($this->currentSeason);
        $this->set(
            'tabs',
            [
            'players' => ['label' => 'Giocatori', 'url' => Router::url(['_name' => 'Team.members', 'id' => $id])],
            'transferts' => ['label' => 'Trasferimenti', 'url' => Router::url(['_name' => 'Team.transferts', 'id' => $id])],
            'last-lineup' => ['label' => 'Ultima giornata', 'url' => Router::url(['_name' => 'Scores.view', 'matchday_id' => $matchday_id, 'team_id' => $id])],
            'articles' => ['label' => 'Articoli', 'url' => Router::url(['_name' => 'Team.articles', 'id' => $id])]
            ]
        );
        $this->set('members', $team->members);
        $this->set('title', $team->name);
        $this->set('team', $team);
        $this->set('_serialize', ['team']);
    }

    /**
     * Add method
     *
     * @return Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $team = $this->Teams->newEntity();
        if ($this->request->is('post')) {
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
        $this->set('_serialize', ['team']);
    }

    /**
     * Edit method
     *
     * @param  string|null $id Team id.
     * @return Response|void Redirects on successful edit, renders view otherwise.
     * @throws NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->log(print_r($this->request, 1), \Psr\Log\LogLevel::INFO);
        $team = $this->Teams->get(
            $id,
            [
            'contain' => ['Members']
            ]
        );
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
        $this->set('_serialize', ['team']);
    }

    /**
     * Delete method
     *
     * @param  string|null $id Team id.
     * @return Response|null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $team = $this->Teams->get($id);
        if ($this->Teams->delete($team)) {
            $this->Flash->success(__('The team has been deleted.'));
        } else {
            $this->Flash->error(__('The team could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
