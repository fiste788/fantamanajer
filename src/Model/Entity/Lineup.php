<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Lineup Entity
 *
 * @property int $id
 * @property string $module
 * @property bool $jolly
 * @property int $captain_id
 * @property int $vcaptain_id
 * @property int $vvcaptain_id
 * @property int $matchday_id
 * @property int $team_id
 *
 * @property \App\Model\Entity\Member $member
 * @property \App\Model\Entity\Matchday $matchday
 * @property \App\Model\Entity\Team $team
 * @property \App\Model\Entity\Disposition[] $dispositions
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 * @property int $old_id
 * @property \App\Model\Entity\Member $captain
 * @property \App\Model\Entity\Member $v_captain
 * @property \App\Model\Entity\Member $v_v_captain
 * @property \App\Model\Entity\Score $score
 */
class Lineup extends Entity
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
        'id' => false
    ];
}
