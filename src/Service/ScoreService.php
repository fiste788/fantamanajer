<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Team;
use Cake\ORM\Locator\LocatorAwareTrait;

class ScoreService
{
    use LocatorAwareTrait;

    /**
     * Create missing points
     *
     * @param \App\Model\Entity\Team $team Team
     * @return array<\App\Model\Entity\Score>
     * @psalm-return list<\App\Model\Entity\Score>
     * @throws \Cake\Core\Exception\CakeException
     */
    public function createMissingPoints(Team $team): array
    {
        /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
        $matchdaysTable = $this->fetchTable('Matchdays');
        /** @var \App\Model\Entity\Matchday $current */
        $current = $matchdaysTable->find('current')->first();
        $matchdaysWithScore = $matchdaysTable->findWithScores($current->season)
            ->orderByAsc('number', true)
            ->distinct()
            ->limit(40);

        /** @var \App\Model\Table\ScoresTable $scoresTable */
        $scoresTable = $this->fetchTable('Scores');
        $scores = [];
        /** @var \App\Model\Entity\Matchday $matchay */
        foreach ($matchdaysWithScore as $matchay) {
            $scores[] = $scoresTable->newEntity([
                'team_id' => $team->id,
                'matchday_id' => $matchay->id,
                'points' => 0,
                'real_points' => 0,
            ]);
        }

        return $scores;
    }
}
