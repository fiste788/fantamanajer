<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use App\Model\Table\ScoresTable;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * 
 * @property ScoresTable $Scores
 */
class ScoresController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['index','view','last','viewByMatchday']);
    }
    
    public function beforeFilter(Event $event)
    {
        $this->Crud->mapAction('index', 'Crud.Index');
    }
    
    public function index()
    {
        $championshipId = $this->request->getParam('championship_id');
        $ranking = $this->Scores->findRankingByChampionshipId($championshipId);
        $scores = $this->Scores->findRankingDetailsByChampionshipId($championshipId);
        
        $this->set([
            'success' => true,
            'data' => [
                'ranking' => $ranking,
                'scores' => $scores
            ],
            '_serialize' => ['data','success']
        ]);
    }
    
    public function last()
    {
        $this->viewByMatchday($this->Scores->findMatchdayWithPoints($this->currentSeason));
    }
    
    public function viewByMatchday($matchdayId)
    {
        $score = $this->Scores->findByMatchdayIdAndTeamId($matchdayId,$this->request->team_id)->first();
        $this->view($score->id);
    }
    
	public function view($id)
    {
        $appo = $this->Scores->get($id);
        $score = $this->Scores->get($id, [
            'contain' => [
                'Lineups' => [
                    'Dispositions' => [
                        'Members' => [
                            'Players', 'Clubs', 'Ratings' => function (\Cake\ORM\Query $q) use ($appo) {
                                return $q->where(['matchday_id' => $appo->matchday_id]);
                            }
                        ]
                    ]
                ]
            ]
        ]);
        /*
        $matchday_id = $score->matchday_id;
        $team_id = $score->team_id;
		$details = TableRegistry::get("Lineups")->findStatsByMatchdayAndTeam($matchday_id,$team_id);
        $maxMatchdays = $this->Scores->findMatchdayWithPoints($this->currentSeason);

        $dispositions = $details->dispositions;
        $regulars = array_splice($dispositions,0,11);
        */
        $this->set([
            'success' => true,
            /*'data' => [
                'score' => $score,
                'details' => $details
            ],*/
            'data' => $score,
            '_serialize' => ['success','data']
        ]);
    }
}