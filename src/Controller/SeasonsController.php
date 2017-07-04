<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Seasons Controller
 *
 * @property \App\Model\Table\SeasonsTable $Seasons
 */
class SeasonsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $seasons = $this->paginate($this->Seasons);

        $this->set(compact('seasons'));
        $this->set('_serialize', ['seasons']);
    }

    /**
     * View method
     *
     * @param string|null $id Season id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $season = $this->Seasons->get($id, [
            'contain' => ['Championships', 'Matchdays', 'Members', 'View0LineupsDetails', 'View0Members', 'View1MembersStats', 'View2ClubsStats']
        ]);

        $this->set('season', $season);
        $this->set('_serialize', ['season']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $season = $this->Seasons->newEntity();
        if ($this->request->is('post')) {
            $season = $this->Seasons->patchEntity($season, $this->request->data);
            if ($this->Seasons->save($season)) {
                $this->Flash->success(__('The season has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The season could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('season'));
        $this->set('_serialize', ['season']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Season id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $season = $this->Seasons->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $season = $this->Seasons->patchEntity($season, $this->request->data);
            if ($this->Seasons->save($season)) {
                $this->Flash->success(__('The season has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The season could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('season'));
        $this->set('_serialize', ['season']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Season id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $season = $this->Seasons->get($id);
        if ($this->Seasons->delete($season)) {
            $this->Flash->success(__('The season has been deleted.'));
        } else {
            $this->Flash->error(__('The season could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
