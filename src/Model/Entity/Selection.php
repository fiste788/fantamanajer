<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\Table;

/**
 * Selection Entity.
 *
 * @property int $id
 * @property bool $active
 * @property bool $processed
 * @property int $team_id
 * @property \App\Model\Entity\Team $team
 * @property int $matchday_id
 * @property \App\Model\Entity\Matchday $matchday
 * @property int $old_member_id
 * @property int $new_member_id
 * @property Member $oldMember
 * @property Member $newMember
 * @property \App\Model\Entity\Member $old_member
 * @property \App\Model\Entity\Member $new_member
 */
class Selection extends Entity
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
