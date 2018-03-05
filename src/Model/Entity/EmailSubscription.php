<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmailSubscription Entity
 *
 * @property int $id
 * @property bool $score
 * @property bool $lineups
 * @property bool $lost_member
 * @property int $team_id
 *
 * @property \App\Model\Entity\Team $team
 */
class EmailSubscription extends Entity
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
        'score' => true,
        'lineups' => true,
        'lost_member' => true,
        'team_id' => true,
        'team' => true
    ];
}
