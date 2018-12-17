<?php

namespace App\Service;

use App\Model\Entity\Team;
use Cake\ORM\TableRegistry;

class ScoreService
{
    public function createMissingPoints(Team $team)
    {
        $matchdays = TableRegistry::getTableLocator()->get('Matchdays');
        $current = $matchdays->find('current')->first();
        $matchdaysWithScore = $matchdays->findWithScores($current->season)
            ->orderAsc('number',true)
            ->distinct()
            ->limit(40);
        $scores = [];
        foreach($matchdaysWithScore as $matchay) {
            $scores[] = $this->newEntity([
                'team_id' => $team->id,
                'matchday_id' => $matchay->id,
                'points' => 0,
                'real_points' => 0
            ]);
        }
        return $scores;
    }
}
