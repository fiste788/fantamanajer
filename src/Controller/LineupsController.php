<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Lineups Controller
 *
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class LineupsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Members', 'Matchdays', 'Teams']
        ];
        $lineups = $this->paginate($this->Lineups);

        $this->set(compact('lineups'));
        $this->set('_serialize', ['lineups']);
    }

    /**
     * View method
     *
     * @param string|null $id Lineup id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $lineup = $this->Lineups->get($id, [
            'contain' => ['Members', 'Matchdays', 'Teams', 'Dispositions', 'View0LineupsDetails']
        ]);

        $this->set('lineup', $lineup);
        $this->set('_serialize', ['lineup']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $lineup = $this->Lineups->newEntity();
        if ($this->request->is('post')) {
            $lineup = $this->Lineups->patchEntity($lineup, $this->request->data);
            if ($this->Lineups->save($lineup)) {
                $this->Flash->success(__('The lineup has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lineup could not be saved. Please, try again.'));
            }
        }
        $members = $this->Lineups->Members->find('list', ['limit' => 200]);
        $matchdays = $this->Lineups->Matchdays->find('list', ['limit' => 200]);
        $teams = $this->Lineups->Teams->find('list', ['limit' => 200]);
        $this->set(compact('lineup', 'members', 'matchdays', 'teams'));
        $this->set('_serialize', ['lineup']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Lineup id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $lineup = $this->Lineups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $lineup = $this->Lineups->patchEntity($lineup, $this->request->data);
            if ($this->Lineups->save($lineup)) {
                $this->Flash->success(__('The lineup has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lineup could not be saved. Please, try again.'));
            }
        }
        $members = $this->Lineups->Members->find('list', ['limit' => 200]);
        $matchdays = $this->Lineups->Matchdays->find('list', ['limit' => 200]);
        $teams = $this->Lineups->Teams->find('list', ['limit' => 200]);
        $this->set(compact('lineup', 'members', 'matchdays', 'teams'));
        $this->set('_serialize', ['lineup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Lineup id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $lineup = $this->Lineups->get($id);
        if ($this->Lineups->delete($lineup)) {
            $this->Flash->success(__('The lineup has been deleted.'));
        } else {
            $this->Flash->error(__('The lineup could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
