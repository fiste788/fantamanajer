<?php
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
     * @param int $role
     * @param bool $playmaker
     */
    public function calcNoBonusPoints($role, $playmaker)
    {
        $minus = 0;
        for ($i = 0; $i < $this->goals; $i++) {
            switch ($role) {
                case 3:
                    if ($playmaker) {
                        $minus += 0.5;
                    }
                    break;
                case 2:
                    if (!$playmaker) {
                        $minus += 1;
                    }
                    break;
                case 1:
                    $minus += 1.5;
                    break;
                case 0:
                    $minus += 2;
                    break;
            }
            //die($minus);
        }
            $this->points_no_bonus = $this->points - $minus;
    }
}
