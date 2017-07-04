<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\PlayersTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;

/**
 * Players Controller
 *
 * @property PlayersTable $Players
 */
class PlayersController extends AppController
{

    /**
     * Index method
     *
     * @return Response|null
     */
    public function index()
    {
        $players = $this->paginate($this->Players);

        $this->set(compact('players'));
        $this->set('_serialize', ['players']);
    }

    /**
     * View method
     *
     * @param string|null $id Player id.
     * @return Response|null
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $player = $this->Players->get($id, [
            'contain' => ['Members']
        ]);
        $members = TableRegistry::get('Members');
        $currentMember = $members->findByPlayerIdAndSeasonId($id,$this->currentSeason->id)
                    ->contain(['Clubs','Ratings' => ['Matchdays'],'Roles'])
                    ->first();
        
        $this->set('currentMember', $currentMember);
        $this->set('player', $player);
        $this->set('_serialize', ['player']);
    }

    /**
     * Add method
     *
     * @return Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $player = $this->Players->newEntity();
        if ($this->request->is('post')) {
            $player = $this->Players->patchEntity($player, $this->request->data);
            if ($this->Players->save($player)) {
                $this->Flash->success(__('The player has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The player could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('player'));
        $this->set('_serialize', ['player']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Player id.
     * @return Response|void Redirects on successful edit, renders view otherwise.
     * @throws NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $player = $this->Players->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $player = $this->Players->patchEntity($player, $this->request->data);
            if ($this->Players->save($player)) {
                $this->Flash->success(__('The player has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The player could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('player'));
        $this->set('_serialize', ['player']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Player id.
     * @return Response|null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $player = $this->Players->get($id);
        if ($this->Players->delete($player)) {
            $this->Flash->success(__('The player has been deleted.'));
        } else {
            $this->Flash->error(__('The player could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
