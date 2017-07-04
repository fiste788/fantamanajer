<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Selections Controller
 *
 * @property \App\Model\Table\SelectionsTable $Selections
 */
class SelectionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Teams', 'Members']
        ];
        $selections = $this->paginate($this->Selections);

        $this->set(compact('selections'));
        $this->set('_serialize', ['selections']);
    }

    /**
     * View method
     *
     * @param string|null $id Selection id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $selection = $this->Selections->get($id, [
            'contain' => ['Teams', 'Members']
        ]);

        $this->set('selection', $selection);
        $this->set('_serialize', ['selection']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $selection = $this->Selections->newEntity();
        if ($this->request->is('post')) {
            $selection = $this->Selections->patchEntity($selection, $this->request->data);
            if ($this->Selections->save($selection)) {
                $this->Flash->success(__('The selection has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The selection could not be saved. Please, try again.'));
            }
        }
        $teams = $this->Selections->Teams->find('list', ['limit' => 200]);
        $members = $this->Selections->Members->find('list', ['limit' => 200]);
        $this->set(compact('selection', 'teams', 'members'));
        $this->set('_serialize', ['selection']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Selection id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $selection = $this->Selections->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $selection = $this->Selections->patchEntity($selection, $this->request->data);
            if ($this->Selections->save($selection)) {
                $this->Flash->success(__('The selection has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The selection could not be saved. Please, try again.'));
            }
        }
        $teams = $this->Selections->Teams->find('list', ['limit' => 200]);
        $members = $this->Selections->Members->find('list', ['limit' => 200]);
        $this->set(compact('selection', 'teams', 'members'));
        $this->set('_serialize', ['selection']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Selection id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $selection = $this->Selections->get($id);
        if ($this->Selections->delete($selection)) {
            $this->Flash->success(__('The selection has been deleted.'));
        } else {
            $this->Flash->error(__('The selection could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
