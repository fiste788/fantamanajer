<?php

namespace App\Service;

use App\Model\Entity\Disposition;
use App\Model\Entity\Lineup;
use App\Model\Entity\Matchday;
use App\Model\Entity\Score;
use App\Model\Entity\Team;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\ORM\TableRegistry;
use PDOException;

/**
 * @property LineupService Lineup
 */
class ComputeScoreService
{
    use ServiceAwareTrait;
    
    public function __construct()
    {
        $this->loadService("Lineup");
    }
    
    /**
     *
     * @param Team     $team
     * @param Matchday $matchday
     * @return Score
     * @throws PDOException
     */
    public function computeScore(Team $team, Matchday $matchday)
    {
        $score = $this->find()
            ->where(['team_id' => $team->id, 'matchday_id' => $matchday->id])
            ->first();
        if (!$score) {
            $score = $this->newEntity([
                'penality_points' => 0,
                'matchday_id' => $matchday->id,
                'team_id' => $team->id
            ]);
        }
        $score->matchday = $matchday;
        $score->team = $team;
        $score->compute();

        return $score;
    }
    
    public function exec(Score $score)
    {
        if(!$score->team) {
            $teams = TableRegistry::getTableLocator()->get('Teams');
            $score->team = $teams->get($score->team_id, ['contain' => ['Championships']]);
        }
        $championship = $score->team->championship;
        $lineups = TableRegistry::get('Lineups');
        if(!$score->lineup) {
            $score->lineup = $lineups->find('last', [
                'matchday' => $score->matchday,
                'team_id' => $score->team->id
            ])->find('withRatings', ['matchday_id' => $score->matchday_id])->first();
        } else {
            $score->lineup = $lineups->loadInto($score->lineup, $lineups->find('withRatings', ['matchday_id' => $score->matchday_id])->getContain());
        }
        if ($score->lineup == null || ($score->lineup->matchday_id != $score->matchday->id && $championship->points_missed_lineup == 0)) {
            $score->real_points = 0;
            $score->points = 0;
        } else {
            if ($score->lineup->matchday_id != $score->matchday_id) {
                $score->lineup = $this->Lineup->copy($score->lineup, $score->matchday, $championship->captain_missed_lineup);
            }
            $score->real_points = $score->lineup->compute();
            $score->points = ($score->lineup->jolly) ? $score->real_points * 2 : $score->real_points;
            if ($championship->points_missed_lineup != 100 && $score->lineup->cloned) {
                $malusPoints = round((($this->points / 100) * (100 - $championship->points_missed_lineup)), 1);
                $mod = ($malusPoints * 10) % 5;
                $score->penality_points = -(($malusPoints * 10) - $mod) / 10;
                $score->penality = 'Formazione non settata';
            }
            $score->points = $score->points - $score->penality_points;
        }
    }

    /**
     *
     * @return float
     */
    public function compute(Lineup $lineup)
    {
        $sum = 0;
        $cap = null;
        $substitution = 0;
        $notValueds = [];
        $this->resetDispositions($lineup);
        if ($lineup->team->championship->captain) {
            $cap = $this->getActiveCaptain($lineup);
        }
        foreach ($lineup->dispositions as $disposition) {
            $member = $disposition->member;
            if ($disposition->position <= 11) {
                if (!$member->ratings[0]->valued) {
                    $notValueds[] = $member;
                } else {
                    $sum += $disposition->regularize($disposition, $cap);
                }
            } else {
                foreach($notValueds as $key => $notValued) {
                    if ($substitution < 3 && $member->role_id == $notValued->role_id && $member->ratings[0]->valued) {
                        $sum += $this->regularize($disposition, $cap);
                        $substitution ++;
                        unset($notValueds[$key]);
                        break;
                    }
                }
            }
            $lineup->setDirty('dispositions', true);
        }
        

        return $sum;
    }

    private function resetDispositions(Lineup $lineup)
    {
        foreach ($lineup->dispositions as $key => $disposition) {
            $disposition->consideration = 0;
            $lineup->disposition[$key] = $disposition;
        }
    }

    /**
     *
     * @return int
     */
    private function getActiveCaptain(Lineup $lineup)
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
     * @param int $cap Id of the captain. Use it for double the points
     * @return float Points scored
     */
    private function regularize(Disposition $disposition, $cap = null)
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
