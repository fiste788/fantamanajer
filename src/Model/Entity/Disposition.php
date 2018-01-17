<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Disposition Entity.
 *
 * @property int $id
 * @property int $position
 * @property int $consideration
 * @property int $lineup_id
 * @property \App\Model\Entity\Lineup $lineup
 * @property int $member_id
 * @property \App\Model\Entity\Member $member
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \App\Model\Entity\Rating $rating
 */
class Disposition extends Entity
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
     * Set the disposition as regular and return the points scored
     *
     * @param int $cap Id of the captain. Use it for double the points
     * @return float Points scored
     */
    public function regularize($cap = null)
    {
        $this->consideration = 1;
        $points = $this->member->ratings[0]->points_no_bonus;
        if ($cap && $this->member->id == $cap) {
            $this->consideration = 2;
            $points *= 2;
        }

        return $points;
    }

    /**
     * Reset the entity to default value and new
     *
     * @return Disposition
     */
    public function reset()
    {
        unset($this->id);
        unset($this->lineup_id);
        $this->consideration = 0;

        return $this;
    }
}
