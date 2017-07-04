<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Form\FreePlayerFilteringForm;
use App\Model\Table\MembersTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;

/**
 * Members Controller
 *
 * @property MembersTable $Members
 */
class MembersController extends AppController
{

    /**
     * Index method
     *
     * @return Response|null
     */
    public function index($id)
    {
        $teams = TableRegistry::get('Teams');
        $team = $teams->get($id, [
			'contain' => ['Members' => ['Seasons', 'Players', 'Clubs', 'Roles']]
		]);
        $members = $team->members;

        $this->set(compact('members'));
        $this->set('_serialize', ['members']);
    }

    /**
     * View method
     *
     * @param string|null $id Member id.
     * @return Response|null
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $member = $this->Members->get($id, [
            'contain' => ['Players', 'Roles', 'Clubs', 'Seasons', 'Teams', 'Dispositions', 'Ratings']
        ]);

        $this->set('member', $member);
        $this->set('_serialize', ['member']);
    }

    /**
     * Add method
     *
     * @return Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $member = $this->Members->newEntity();
        if ($this->request->is('post')) {
            $member = $this->Members->patchEntity($member, $this->request->data);
            if ($this->Members->save($member)) {
                $this->Flash->success(__('The member has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The member could not be saved. Please, try again.'));
            }
        }
        $players = $this->Members->Players->find('list', ['limit' => 200]);
        $roles = $this->Members->Roles->find('list', ['limit' => 200]);
        $clubs = $this->Members->Clubs->find('list', ['limit' => 200]);
        $seasons = $this->Members->Seasons->find('list', ['limit' => 200]);
        $teams = $this->Members->Teams->find('list', ['limit' => 200]);
        $this->set(compact('member', 'players', 'roles', 'clubs', 'seasons', 'teams'));
        $this->set('_serialize', ['member']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Member id.
     * @return Response|void Redirects on successful edit, renders view otherwise.
     * @throws NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $member = $this->Members->get($id, [
            'contain' => ['Teams']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $member = $this->Members->patchEntity($member, $this->request->data);
            if ($this->Members->save($member)) {
                $this->Flash->success(__('The member has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The member could not be saved. Please, try again.'));
            }
        }
        $players = $this->Members->Players->find('list', ['limit' => 200]);
        $roles = $this->Members->Roles->find('list', ['limit' => 200]);
        $clubs = $this->Members->Clubs->find('list', ['limit' => 200]);
        $seasons = $this->Members->Seasons->find('list', ['limit' => 200]);
        $teams = $this->Members->Teams->find('list', ['limit' => 200]);
        $this->set(compact('member', 'players', 'roles', 'clubs', 'seasons', 'teams'));
        $this->set('_serialize', ['member']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Member id.
     * @return Response|null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $member = $this->Members->get($id);
        if ($this->Members->delete($member)) {
            $this->Flash->success(__('The member has been deleted.'));
        } else {
            $this->Flash->error(__('The member could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function free($championshipId) {
        $defaultRole = $this->request->param('role') ||  1;
        /*$defaultMatch = array_key_exists('match', $this->request->data) ? $this->request->data['match'] : (floor(($this->currentMatchday->number - 1) / 2) + 1);
        $defaultEnough = array_key_exists('enough', $this->request->data) ? $this->request->data['enough'] :  6;
        
        //die(var_dump($this->request->param('enough')));
        $filter = new FreePlayerFilteringForm();
         * if ($this->request->is('get')) {
            $this->request->data['enough'] = $defaultEnough;
            $this->request->data['match'] = $defaultMatch;
        }
         */
        $role = TableRegistry::get('Roles')->get($defaultRole);
        $members = $this->Members->findFree($championshipId)->where(['role_id' => $defaultRole]);
        
        //$this->set('filter', $filter);
        $this->set('role', $role);
        $this->set('members', $members);
        $this->set('_serialize', ['members']);
    }
}
