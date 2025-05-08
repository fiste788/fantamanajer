<?php
declare(strict_types=1);

namespace App\Service;

use AllowDynamicProperties;
use App\Model\Entity\Disposition;
use App\Model\Entity\Lineup;
use App\Model\Entity\Matchday;
use App\Model\Entity\Score;
use App\Model\Entity\Team;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * @property \App\Service\LineupService $Lineup
 */
#[AllowDynamicProperties]
class ComputeScoreService
{
    use LocatorAwareTrait;
    use ServiceAwareTrait;

    /**
     * Undocumented function
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->loadService('Lineup');
    }

    /**
     * @param \App\Model\Entity\Team $team The team
     * @param \App\Model\Entity\Matchday $matchday The matchday
     * @return \App\Model\Entity\Score
     * @throws \PDOException
     * @throws \Cake\Core\Exception\CakeException
     */
    public function computeScore(Team $team, Matchday $matchday): Score
    {
        /** @var \App\Model\Table\ScoresTable $scoresTable */
        $scoresTable = $this->fetchTable('Scores');
        /** @var \App\Model\Entity\Score|null $score */
        $score = $scoresTable->find()
            ->where(['team_id' => $team->id, 'matchday_id' => $matchday->id])
            ->first();
        if ($score == null) {
            $score = $scoresTable->newEntity([
                'penality_points' => 0,
                'matchday_id' => $matchday->id,
                'team_id' => $team->id,
            ], ['accessibleFields' => ['*' => true]]);
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
     * @throws \Cake\Core\Exception\CakeException
     */
    public function exec(Score $score): void
    {
        /** @var \App\Model\Table\TeamsTable $teamsTable */
        $teamsTable = $this->fetchTable('Teams');
        /**
         * @psalm-suppress DocblockTypeContradiction
         * @psalm-suppress RedundantConditionGivenDocblockType
         */
        $score->team = $score->team ?? $teamsTable->get($score->team_id, contain: ['Championships']);
        /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
        $matchdaysTable = $this->fetchTable('Matchdays');
        /**
         * @psalm-suppress DocblockTypeContradiction
         * @psalm-suppress RedundantConditionGivenDocblockType
         */
        $score->matchday = $score->matchday ?? $matchdaysTable->get($score->matchday_id, contain: ['Seasons']);
        /** @var \App\Model\Table\SeasonsTable $seasonsTable */
        $seasonsTable = $this->fetchTable('Seasons');
        $season = $score->matchday->has('season') ?
            $score->matchday->season : $seasonsTable->get($score->matchday->season_id);
        $championship = $score->team->championship;
        $lineup = $score->lineup;

        /** @var \App\Model\Table\LineupsTable $lineupsTable */
        $lineupsTable = $this->fetchTable('Lineups');
        if ($lineup == null) {
            /** @var \App\Model\Entity\Lineup|null $lineup */
            $lineup = $lineupsTable->find(
                'last',
                matchday: $score->matchday,
                team_id: $score->team->id,
            )->find('withRatings', matchday_id: $score->matchday_id)->first();
        } else {
            /** @var \App\Model\Entity\Lineup $lineup */
            $lineup = $lineupsTable->loadInto(
                $lineup,
                $lineupsTable->find(
                    'withRatings',
                    matchday_id: $score->matchday_id,
                )->getContain(),
            );
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
                        $championship->captain_missed_lineup,
                    );
                }
            }
            if ($lineup != null) {
                $score->real_points = $this->compute(
                    $lineup,
                    $season->bonus_points_goals && !$championship->bonus_points_goals,
                    $season->bonus_points_clean_sheet && !$championship->bonus_points_clean_sheet,
                );
                $score->points = $lineup->jolly ? $score->real_points * 2 : $score->real_points;
                if ($championship->points_missed_lineup != 100 && $lineup->cloned) {
                    $malusPoints = round($score->points / 100 * (100 - (float)$championship->points_missed_lineup), 1);
                    $mod = ($malusPoints * 10) % 5;
                    $score->penality_points = - (($malusPoints * 10) - $mod) / 10;
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
     * @param bool $subtractBonusGoals Subtract bonus goals
     * @param bool $subtractBonusCleanSheet Subtract bonus clean sheet
     * @return float
     */
    public function compute(
        Lineup $lineup,
        bool $subtractBonusGoals = false,
        bool $subtractBonusCleanSheet = false,
    ): float {
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
            if ($member) {
                if ($disposition->position <= 11) {
                    if (empty($member->ratings) || !$member->ratings[0]->valued) {
                        $notValueds[] = $member;
                    } else {
                        $sum += $this->regularize($disposition, $subtractBonusGoals, $subtractBonusCleanSheet, $cap);
                    }
                } else {
                    foreach ($notValueds as $key => $notValued) {
                        $ratings = $member->ratings;
                        if (
                            $substitution < $lineup->team->championship->number_substitutions &&
                            $member->role_id == $notValued->role_id &&
                            !empty($ratings) &&
                            $ratings[0]->valued
                        ) {
                            $sum += $this->regularize(
                                $disposition,
                                $subtractBonusGoals,
                                $subtractBonusCleanSheet,
                                $cap,
                            );
                            $substitution++;
                            unset($notValueds[$key]);
                            break;
                        }
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
            if ($cap != null) {
                $dispositions = array_filter(
                    $lineup->dispositions,
                    function ($value) use ($cap) {
                        return $value->member_id == $cap;
                    },
                );
                $disposition = array_shift($dispositions);
                if ($disposition && $disposition->member && $disposition->member->ratings[0]->valued) {
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
     * @param bool $subtractBonusGoals Subtract bonus goals
     * @param bool $subtractBonusCleanSheet Subtract bonus clean sheet
     * @param int $cap Id of the captain. Use it for double the points
     * @return float Points scored
     */
    private function regularize(
        Disposition $disposition,
        bool $subtractBonusGoals,
        bool $subtractBonusCleanSheet,
        ?int $cap = null,
    ): float {
        if ($disposition->member) {
            $disposition->consideration = 1;

            $rating = $disposition->member->ratings[0];
            $points = $rating->points;
            if ($subtractBonusGoals) {
                $points -= $rating->getBonusPointsGoals($disposition->member);
            }
            if ($subtractBonusCleanSheet) {
                $points -= $rating->getBonusCleanSheetPoints($disposition->member);
            }
            $disposition->points = $points;
            if ($cap != null && $disposition->member->id == $cap) {
                $disposition->consideration = 2;
                $points *= 2;
            }

            return $points;
        }

        return 0;
    }
}
