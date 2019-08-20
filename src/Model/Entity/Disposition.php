<?php
declare(strict_types=1);

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
}
