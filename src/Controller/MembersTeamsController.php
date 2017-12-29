<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * MembersTeams Controller
 *
 * @property \App\Model\Table\MembersTeamsTable $MembersTeams
 */
class MembersTeamsController extends AppController
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
        $membersTeams = $this->paginate($this->MembersTeams);

        $this->set(compact('membersTeams'));
        $this->set('_serialize', ['membersTeams']);
    }

    /**
     * View method
     *
     * @param  string|null $id Members Team id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $membersTeam = $this->MembersTeams->get(
            $id,
            [
            'contain' => ['Teams', 'Members']
            ]
        );

        $this->set('membersTeam', $membersTeam);
        $this->set('_serialize', ['membersTeam']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $membersTeam = $this->MembersTeams->newEntity();
        if ($this->request->is('post')) {
            $membersTeam = $this->MembersTeams->patchEntity($membersTeam, $this->request->data);
            if ($this->MembersTeams->save($membersTeam)) {
                $this->Flash->success(__('The members team has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The members team could not be saved. Please, try again.'));
            }
        }
        $teams = $this->MembersTeams->Teams->find('list', ['limit' => 200]);
        $members = $this->MembersTeams->Members->find('list', ['limit' => 200]);
        $this->set(compact('membersTeam', 'teams', 'members'));
        $this->set('_serialize', ['membersTeam']);
    }

    /**
     * Edit method
     *
     * @param  string|null $id Members Team id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $membersTeam = $this->MembersTeams->get(
            $id,
            [
            'contain' => []
            ]
        );
        if ($this->request->is(['patch', 'post', 'put'])) {
            $membersTeam = $this->MembersTeams->patchEntity($membersTeam, $this->request->data);
            if ($this->MembersTeams->save($membersTeam)) {
                $this->Flash->success(__('The members team has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The members team could not be saved. Please, try again.'));
            }
        }
        $teams = $this->MembersTeams->Teams->find('list', ['limit' => 200]);
        $members = $this->MembersTeams->Members->find('list', ['limit' => 200]);
        $this->set(compact('membersTeam', 'teams', 'members'));
        $this->set('_serialize', ['membersTeam']);
    }

    /**
     * Delete method
     *
     * @param  string|null $id Members Team id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $membersTeam = $this->MembersTeams->get($id);
        if ($this->MembersTeams->delete($membersTeam)) {
            $this->Flash->success(__('The members team has been deleted.'));
        } else {
            $this->Flash->error(__('The members team could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
