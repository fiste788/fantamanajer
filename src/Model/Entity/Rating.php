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
    public function calcNoBonusPoints()
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
            //die($minus);
        }

        return $this->points - $minus;
    }
}
