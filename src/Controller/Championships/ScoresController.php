<?php
namespace App\Controller\Championships;

use App\Controller\AppController;

/**
 *
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoresController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index']);
    }

    public function index()
    {
        $championshipId = $this->request->getParam('championship_id');
        $ranking = $this->Scores->findRanking($championshipId);
        $scores = $this->Scores->findRankingDetails($championshipId);

        $this->set(
            [
            'success' => true,
            'data' => [
                'ranking' => $ranking,
                'scores' => $scores
            ],
            '_serialize' => ['data', 'success']
            ]
        );
    }
}
