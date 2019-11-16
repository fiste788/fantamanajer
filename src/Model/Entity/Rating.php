<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rating Entity.
 *
 * @property int $id
 * @property bool $valued
 * @property float $points
 * @property float $points_no_bonus
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
 * @property \App\Model\Entity\Member $member
 * @property int $matchday_id
 * @property \App\Model\Entity\Matchday $matchday
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
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     *
     * @return float
     */
    public function getBonusPoints()
    {
        $minus = 0;
        for ($i = 0; $i < $this->goals; $i++) {
            switch ($this->member->role->abbreviation) {
                case 'A':
                    if ($this->member->playmaker) {
                        $minus += 0.5;
                    }
                    break;
                case 'C':
                    if (!$this->member->playmaker) {
                        $minus += 1;
                    } else {
                        $minus += 0.5;
                    }
                    break;
                case 'D':
                    $minus += 1.5;
                    break;
                case 'P':
                    $minus += 2;
                    break;
            }
        }

        return $$minus;
    }

    /**
     *
     * @return float
     */
    public function getBonusCleanSheetPoints()
    {
        $minus = 0;
        if ($this->goals_against == 0 && $this->member->role->abbreviation == 'P') {
            $minus += 1;
        }

        return $$minus;
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Season $season Season
     * @param bool $force Force
     * @return float
     */
    public function calcPointsNoBonus(Season $season, bool $force = false)
    {
        $pointsNoBonus = $this->points;
        if ($season->bonus_points || $force) {
            $pointsNoBonus -= $this->getBonusPoints();
        }
        if ($season->bonus_points_clean_sheet || $force) {
            $pointsNoBonus -= $this->getBonusCleanSheetPoints();
        }

        return $pointsNoBonus;
    }
}
