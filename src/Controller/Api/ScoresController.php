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
        $this->view($score != null ? $score->id : null);
    }
    
	public function view($id)
    {
        $score = null;
        if($id != null) {
            $appo = $this->Scores->get($id);
            $score = $this->Scores->get($id, [
                'contain' => [
                    'Lineups' => [
                        'Dispositions' => [
                            'Members' => [
                                'Roles', 'Players', 'Clubs', 'Ratings' => function (\Cake\ORM\Query $q) use ($appo) {
                                    return $q->where(['matchday_id' => $appo->matchday_id]);
                                }
                            ]
                        ]
                    ]
                ]
            ]);
        }
       
        $this->set([
            'success' => true,
            'data' => $score,
            '_serialize' => ['success','data']
        ]);
    }
}