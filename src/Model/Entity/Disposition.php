<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Disposition Entity
 *
 * @property int $id
 * @property int $position
 * @property int $consideration
 * @property int $lineup_id
 * @property int $member_id
 * 
 * @property \App\Model\Entity\Rating $rating
 * @property \App\Model\Entity\Lineup $lineup
 * @property \App\Model\Entity\Member $member
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
        'position' => true,
        'consideration' => true,
        'lineup_id' => true,
        'member_id' => true,
        'rating' => false,
        'lineup' => true,
        'member' => true,
    ];
}
