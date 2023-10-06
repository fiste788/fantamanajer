<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rating Entity
 *
 * @property int $id
 * @property bool $valued
 * @property float $points
 * @property float $rating
 * @property int $goals
 * @property int $goals_against
 * @property int $goals_victory
 * @property int $goals_tie
 * @property int $assist
 * @property bool $yellow_card
 * @property bool $red_card
 * @property int $penalities_scored
 * @property int $penalities_taken
 * @property bool $present
 * @property bool $regular
 * @property int $quotation
 * @property int $member_id
 * @property int $matchday_id
 *
 * @property \App\Model\Entity\Member $member
 * @property \App\Model\Entity\Matchday $matchday
 * @property float|null $points_no_bonus
 */
class Rating extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'valued' => false,
        'points' => false,
        'rating' => false,
        'goals' => false,
        'goals_against' => false,
        'goals_victory' => false,
        'goals_tie' => false,
        'assist' => false,
        'yellow_card' => false,
        'red_card' => false,
        'penalities_scored' => false,
        'penalities_taken' => false,
        'present' => false,
        'regular' => false,
        'quotation' => false,
        'member_id' => false,
        'matchday_id' => false,
        'member' => false,
        'matchday' => false,
    ];

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Member|null $member Member
     * @return float
     */
    public function getBonusPointsGoals(?Member $member = null): float
    {
        $minus = (float)0;
        $member = $member ?? $this->member;
        for ($i = 0; $i < $this->goals; $i++) {
            switch ($member->role->abbreviation) {
                case 'A':
                    $minus += $member->playmaker ? 0.5 : 0;
                    break;
                case 'C':
                    $minus += !$member->playmaker ? 1 : 0.5;
                    break;
                case 'D':
                    $minus += 1.5;
                    break;
                case 'P':
                    $minus += 2;
                    break;
            }
        }

        return $minus;
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Member|null $member Member
     * @return float
     */
    public function getBonusCleanSheetPoints(?Member $member = null): float
    {
        $minus = (float)0;
        $member = $member ?? $this->member;
        if ($this->goals_against == 0 && $member->role->abbreviation == 'P' && $this->rating != 0 && $this->valued) {
            $minus += 1;
        }

        return $minus;
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Season $season Season
     * @param bool $force Force
     * @return float
     */
    public function calcPointsNoBonus(Season $season, bool $force = false): float
    {
        $pointsNoBonus = $this->points;
        if ($season->bonus_points_goals || $force) {
            $pointsNoBonus -= $this->getBonusPointsGoals();
        }
        if ($season->bonus_points_clean_sheet || $force) {
            $pointsNoBonus -= $this->getBonusCleanSheetPoints();
        }

        return $pointsNoBonus;
    }
}
