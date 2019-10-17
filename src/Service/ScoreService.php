<?php

namespace App\Service;

use App\Model\Entity\Rating;
use App\Model\Entity\Team;
use Cake\Datasource\ModelAwareTrait;

/**
 *
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoreService
{
    use ModelAwareTrait;

    public function __construct()
    {
        $this->loadModel('Matchdays');
        $this->loadModel('Scores');
    }

    public function createMissingPoints(Team $team)
    {
        $current = $this->Matchdays->find('current')->first();
        $matchdaysWithScore = $this->Matchdays->findWithScores($current->season)
            ->orderAsc('number', true)
            ->distinct()
            ->limit(40);
        $scores = [];
        foreach ($matchdaysWithScore as $matchay) {
            $scores[] = $this->Scores->newEntity([
                'team_id' => $team->id,
                'matchday_id' => $matchay->id,
                'points' => 0,
                'real_points' => 0,
            ]);
        }

        return $scores;
    }
}
