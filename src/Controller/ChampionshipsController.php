<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\ChampionshipsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;
use Cake\Routing\Router;

/**
 * Championships Controller
 *
 * @property ChampionshipsTable $Championships
 */
class ChampionshipsController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow('view');
        $this->loadModel('Championships');
    }

    /**
     * Index method
     *
     * @return Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Leagues', 'Seasons']
        ];
        $championships = $this->paginate($this->Championships);

        $this->set(compact('championships'));
        $this->set('_serialize', ['championships']);
    }

    /**
     * View method
     *
     * @param  string|null $id Championship id.
     * @return Response|null
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $championship = $this->Championships->get(
            $id,
            [
            'contain' => ['Leagues', 'Seasons', 'Teams']
            ]
        );

        $this->request->session()->write("championship", $championship);
        $this->currentChampionship = $championship;
        $this->set(
            'tabs',
            [
            'teams' => ['label' => 'Squadre', 'url' => Router::url(['_name' => 'Championship.teams', 'id' => $id])],
            'ranking' => ['label' => 'Classifica', 'url' => Router::url(['_name' => 'Championship.ranking', 'id' => $id])],
            'free_player' => ['label' => 'Giocatori liberi', 'url' => Router::url(['_name' => 'Championship.freePlayer', 'id' => $id])],
            'articles' => ['label' => 'Articoli', 'url' => Router::url(['_name' => 'Championship.articles', 'id' => $id])]
            ]
        );
        $this->set('title', $championship->league->name);
        $this->set('championship', $championship);
        $this->set('_serialize', ['championship']);
    }

    /**
     * Add method
     *
     * @return Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $championship = $this->Championships->newEntity();
        if ($this->request->is('post')) {
            $championship = $this->Championships->patchEntity($championship, $this->request->data);
            if ($this->Championships->save($championship)) {
                $this->Flash->success(__('The championship has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The championship could not be saved. Please, try again.'));
            }
        }
        $leagues = $this->Championships->Leagues->find('list', ['limit' => 200]);
        $seasons = $this->Championships->Seasons->find('list', ['limit' => 200]);
        $this->set(compact('championship', 'leagues', 'seasons'));
        $this->set('_serialize', ['championship']);
    }

    /**
     * Edit method
     *
     * @param  string|null $id Championship id.
     * @return Response|void Redirects on successful edit, renders view otherwise.
     * @throws NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $championship = $this->Championships->get(
            $id,
            [
            'contain' => []
            ]
        );
        if ($this->request->is(['patch', 'post', 'put'])) {
            $championship = $this->Championships->patchEntity($championship, $this->request->data);
            if ($this->Championships->save($championship)) {
                $this->Flash->success(__('The championship has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The championship could not be saved. Please, try again.'));
            }
        }
        $leagues = $this->Championships->Leagues->find('list', ['limit' => 200]);
        $seasons = $this->Championships->Seasons->find('list', ['limit' => 200]);
        $this->set(compact('championship', 'leagues', 'seasons'));
        $this->set('_serialize', ['championship']);
    }

    /**
     * Delete method
     *
     * @param  string|null $id Championship id.
     * @return Response|null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $championship = $this->Championships->get($id);
        if ($this->Championships->delete($championship)) {
            $this->Flash->success(__('The championship has been deleted.'));
        } else {
            $this->Flash->error(__('The championship could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
