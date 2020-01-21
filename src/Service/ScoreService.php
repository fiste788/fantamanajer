<?php
declare(strict_types=1);

namespace App\Service;

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

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->loadModel('Matchdays');
        $this->loadModel('Scores');
    }

    /**
     * Create missing points
     *
     * @param \App\Model\Entity\Team $team Team
     *
     * @return \App\Model\Entity\Score[]
     *
     * @psalm-return list<\App\Model\Entity\Score>
     */
    public function createMissingPoints(Team $team): array
    {
        /** @var \App\Model\Entity\Matchday $current */
        $current = $this->Matchdays->find('current')->first();
        $matchdaysWithScore = $this->Matchdays->findWithScores($current->season)
            ->orderAsc('number', true)
            ->distinct()
            ->limit(40);
        $scores = [];
        /** @var \App\Model\Entity\Matchday $matchay */
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
