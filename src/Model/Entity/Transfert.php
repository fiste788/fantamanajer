<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Transfert Entity.
 *
 * @property int $id
 * @property int $old_member_id
 * @property int $new_member_id
 * @property Member $member
 * @property int $team_id
 * @property Team $team
 * @property int $matchday_id
 * @property Matchday $matchday
 * @property bool $constrained
 * @property Member $old_member
 * @property Member $new_member
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
        '*' => true,
        'id' => false,
    ];
}
