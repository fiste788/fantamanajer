<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transfert Entity
 *
 * @property int $id
 * @property int $old_member_id
 * @property int $new_member_id
 * @property int $team_id
 * @property int $matchday_id
 * @property bool $constrained
 *
 * @property \App\Model\Entity\Member $old_member
 * @property \App\Model\Entity\Member $new_member
 * @property \App\Model\Entity\Team $team
 * @property \App\Model\Entity\Matchday $matchday
 */
class Transfert extends Entity
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
        'old_member_id' => true,
        'new_member_id' => true,
        'team_id' => true,
        'matchday_id' => true,
        'constrained' => true,
        'old_member' => true,
        'new_member' => true,
        'team' => true,
        'matchday' => true,
    ];
}
