<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\ScoresTable;
use Cake\Collection\Collection;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;

/**
 * Scores Controller
 *
 * @property ScoresTable $Scores
 */
class ScoresController extends AppController
{

    /**
     * Index method
     *
     * @return Response|null
     */
    public function index($championshipId)
    {
        $ranking = $this->Scores->findRankingByChampionshipId($championshipId);
        $scores = $this->Scores->findRankingDetailsByChampionshipId($championshipId);
        
        $this->set('ranking', $ranking);
        $this->set('scores', $scores);
        $this->set('_serialize', ['ranking']);
    }

    /**
     * View method
     *
     * @param string|null $id Score id.
     * @return Response|null
     * @throws RecordNotFoundException When record not found.
     */
    public function view($matchday_id,$team_id)
    {
		$details = TableRegistry::get("Lineups")->findStatsByMatchdayAndTeam($matchday_id,$team_id);
        $score = $this->Scores->findByMatchdayIdAndTeamId($matchday_id,$team_id)->first();
        $maxMatchdays = $this->Scores->findMatchdayWithPoints($this->currentSeason);

        $dispositions = $details->dispositions;
        $regulars = array_splice($dispositions,0,11);
        
        $this->set('score', $score);
        $this->set('details', $details);
        $this->set('regulars', $regulars);
        $this->set('notRegulars', $dispositions);
        $this->set('_serialize', ['score']);
    }

    /**
     * Add method
     *
     * @return Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $score = $this->Scores->newEntity();
        if ($this->request->is('post')) {
            $score = $this->Scores->patchEntity($score, $this->request->data);
            if ($this->Scores->save($score)) {
                $this->Flash->success(__('The score has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The score could not be saved. Please, try again.'));
            }
        }
        $teams = $this->Scores->Teams->find('list', ['limit' => 200]);
        $matchdays = $this->Scores->Matchdays->find('list', ['limit' => 200]);
        $this->set(compact('score', 'teams', 'matchdays'));
        $this->set('_serialize', ['score']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Score id.
     * @return Response|void Redirects on successful edit, renders view otherwise.
     * @throws NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $score = $this->Scores->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $score = $this->Scores->patchEntity($score, $this->request->data);
            if ($this->Scores->save($score)) {
                $this->Flash->success(__('The score has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The score could not be saved. Please, try again.'));
            }
        }
        $teams = $this->Scores->Teams->find('list', ['limit' => 200]);
        $matchdays = $this->Scores->Matchdays->find('list', ['limit' => 200]);
        $this->set(compact('score', 'teams', 'matchdays'));
        $this->set('_serialize', ['score']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Score id.
     * @return Response|null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $score = $this->Scores->get($id);
        if ($this->Scores->delete($score)) {
            $this->Flash->success(__('The score has been deleted.'));
        } else {
            $this->Flash->error(__('The score could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
