<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Disposition;
use App\Model\Entity\Lineup;
use App\Model\Entity\Matchday;
use App\Model\Entity\Score;
use App\Model\Entity\Team;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Datasource\ModelAwareTrait;

/**
 * @property \App\Service\LineupService $Lineup
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Model\Table\TeamsTable $Teams
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class ComputeScoreService
{
    use ModelAwareTrait;
    use ServiceAwareTrait;

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->loadService("Lineup");
        $this->loadModel("Scores");
        $this->loadModel("Teams");
        $this->loadModel("Lineups");
    }

    /**
     *
     * @param \App\Model\Entity\Team $team The team
     * @param \App\Model\Entity\Matchday $matchday The matchday
     * @return \App\Model\Entity\Score
     * @throws \PDOException
     */
    public function computeScore(Team $team, Matchday $matchday): Score
    {
        /** @var \App\Model\Entity\Score|null $score */
        $score = $this->Scores->find()
            ->where(['team_id' => $team->id, 'matchday_id' => $matchday->id])
            ->first();
        if ($score == null) {
            $score = $this->Scores->newEntity([
                'penality_points' => 0,
                'matchday_id' => $matchday->id,
                'team_id' => $team->id,
            ]);
        }
        $score->matchday = $matchday;
        $score->team = $team;
        $this->exec($score);

        return $score;
    }

    /**
     * Calculate the score
     *
     * @param \App\Model\Entity\Score $score Score entity
     * @return void
     */
    public function exec(Score $score): void
    {
        $score->team = $score->team ?? $this->Teams->get($score->team_id, ['contain' => ['Championships']]);
        $championship = $score->team->championship;
        $lineup = $score->lineup;
        if ($lineup == null) {
            /** @var \App\Model\Entity\Lineup|null $lineup */
            $lineup = $this->Lineups->find('last', [
                'matchday' => $score->matchday,
                'team_id' => $score->team->id,
            ])->find('withRatings', ['matchday_id' => $score->matchday_id])->first();
        } else {
            /** @var \App\Model\Entity\Lineup $lineup */
            $lineup = $this->Lineups->loadInto($lineup, $this->Lineups->find('withRatings', [
                'matchday_id' => $score->matchday_id,
            ])->getContain());
        }
        if ($lineup == null) {
            $score->real_points = 0;
            $score->points = 0;
        } else {
            if ($lineup->matchday_id != $score->matchday_id) {
                if ($championship->points_missed_lineup == 0) {
                    $lineup = null;
                    $score->real_points = 0;
                    $score->points = 0;
                } else {
                    $lineup = $this->Lineup->copy(
                        $lineup,
                        $score->matchday,
                        $championship->captain_missed_lineup
                    );
                }
            }
            if ($lineup != null) {
                $score->real_points = $this->compute($lineup);
                $score->points = $lineup->jolly ? $score->real_points * 2 : $score->real_points;
                if ($championship->points_missed_lineup != 100 && $lineup->cloned) {
                    $malusPoints = round(($score->points / 100) * (100 - $championship->points_missed_lineup), 1);
                    $mod = ($malusPoints * 10) % 5;
                    $score->penality_points = -(($malusPoints * 10) - $mod) / 10;
                    $score->penality = 'Formazione non settata';
                }
                $score->points = $score->points - $score->penality_points;
            }
        }
        $score->lineup = $lineup;
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Lineup $lineup The lineup to calc
     * @return float
     */
    public function compute(Lineup $lineup): float
    {
        $sum = 0;
        $cap = null;
        $substitution = 0;
        $notValueds = [];
        $this->Lineup->resetDispositions($lineup);
        if ($lineup->team->championship->captain) {
            $cap = $this->getActiveCaptain($lineup);
        }
        foreach ($lineup->dispositions as $disposition) {
            $member = $disposition->member;
            if ($disposition->position <= 11) {
                if (!$member->ratings[0]->valued) {
                    $notValueds[] = $member;
                } else {
                    $sum += $this->regularize($disposition, $cap);
                }
            } else {
                foreach ($notValueds as $key => $notValued) {
                    if ($substitution < 3 && $member->role_id == $notValued->role_id && $member->ratings[0]->valued) {
                        $sum += $this->regularize($disposition, $cap);
                        $substitution++;
                        unset($notValueds[$key]);
                        break;
                    }
                }
            }
            $lineup->setDirty('dispositions', true);
        }

        return $sum;
    }

    /**
     * Return the id of the captain
     *
     * @param \App\Model\Entity\Lineup $lineup The lineup
     * @return int|null
     */
    private function getActiveCaptain(Lineup $lineup): ?int
    {
        $captains = [$lineup->captain_id, $lineup->vcaptain_id, $lineup->vvcaptain_id];
        foreach ($captains as $cap) {
            if ($cap) {
                $dispositions = array_filter(
                    $lineup->dispositions,
                    function ($value) use ($cap) {
                        return $value->member_id == $cap;
                    }
                );
                $disposition = array_shift($dispositions);
                if ($disposition && $disposition->member->ratings[0]->valued) {
                    return $cap;
                }
            }
        }

        return null;
    }

    /**
     * Set the disposition as regular and return the points scored
     *
     * @param \App\Model\Entity\Disposition $disposition The disposition
     * @param int $cap Id of the captain. Use it for double the points
     * @return float Points scored
     */
    private function regularize(Disposition $disposition, $cap = null): float
    {
        $disposition->consideration = 1;
        $points = $disposition->member->ratings[0]->points_no_bonus;
        if ($cap && $disposition->member->id == $cap) {
            $disposition->consideration = 2;
            $points *= 2;
        }

        return $points;
    }
}
