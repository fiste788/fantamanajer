<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Transferts Controller
 *
 * @property \App\Model\Table\TransfertsTable $Transferts
 */
class TransfertsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Members', 'Teams', 'Matchdays']
        ];
        $transferts = $this->paginate($this->Transferts);

        $this->set(compact('transferts'));
        $this->set('_serialize', ['transferts']);
    }

    /**
     * View method
     *
     * @param string|null $id Transfert id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $transfert = $this->Transferts->get($id, [
            'contain' => ['Members', 'Teams', 'Matchdays']
        ]);

        $this->set('transfert', $transfert);
        $this->set('_serialize', ['transfert']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $transfert = $this->Transferts->newEntity();
        if ($this->request->is('post')) {
            $transfert = $this->Transferts->patchEntity($transfert, $this->request->data);
            if ($this->Transferts->save($transfert)) {
                $this->Flash->success(__('The transfert has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The transfert could not be saved. Please, try again.'));
            }
        }
        $members = $this->Transferts->Members->find('list', ['limit' => 200]);
        $teams = $this->Transferts->Teams->find('list', ['limit' => 200]);
        $matchdays = $this->Transferts->Matchdays->find('list', ['limit' => 200]);
        $this->set(compact('transfert', 'members', 'teams', 'matchdays'));
        $this->set('_serialize', ['transfert']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Transfert id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $transfert = $this->Transferts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $transfert = $this->Transferts->patchEntity($transfert, $this->request->data);
            if ($this->Transferts->save($transfert)) {
                $this->Flash->success(__('The transfert has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The transfert could not be saved. Please, try again.'));
            }
        }
        $members = $this->Transferts->Members->find('list', ['limit' => 200]);
        $teams = $this->Transferts->Teams->find('list', ['limit' => 200]);
        $matchdays = $this->Transferts->Matchdays->find('list', ['limit' => 200]);
        $this->set(compact('transfert', 'members', 'teams', 'matchdays'));
        $this->set('_serialize', ['transfert']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Transfert id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transfert = $this->Transferts->get($id);
        if ($this->Transferts->delete($transfert)) {
            $this->Flash->success(__('The transfert has been deleted.'));
        } else {
            $this->Flash->error(__('The transfert could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
