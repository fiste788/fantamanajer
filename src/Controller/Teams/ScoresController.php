<?php
namespace App\Controller\Teams;

class ScoresController extends \App\Controller\ScoresController
{
    public function last()
    {
        $this->viewByMatchday($this->Scores->findMaxMatchday($this->currentSeason));
    }

    public function viewByMatchday($matchdayId)
    {
        $score = $this->Scores->findByMatchdayIdAndTeamId($matchdayId, $this->request->team_id)->first();
        $this->view($score != null ? $score->id : null);
    }
}
