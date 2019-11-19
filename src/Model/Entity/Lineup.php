<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Lineup Entity
 *
 * @property int $id
 * @property string $module
 * @property bool|null $jolly
 * @property bool|null $cloned
 * @property int|null $captain_id
 * @property int|null $vcaptain_id
 * @property int|null $vvcaptain_id
 * @property int $matchday_id
 * @property int $team_id
 *
 * @property \App\Model\Entity\Matchday $matchday
 * @property \App\Model\Entity\Team $team
 * @property \App\Model\Entity\Disposition[] $dispositions
 * @property \Cake\ORM\Entity[] $view0_lineups_details
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property \App\Model\Entity\Member|null $captain
 * @property \App\Model\Entity\Score|null $score
 * @property \App\Model\Entity\Member|null $v_captain
 * @property \App\Model\Entity\Member|null $v_v_captain
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
        'id' => false,
    ];

    /**
     * Fields with the possible modules of a lineup
     *
     * @var array
     */
    public static $modules = [
        '1-4-4-2',
        '1-4-3-3',
        '1-4-5-1',
        '1-3-4-3',
        '1-3-5-2',
        '1-5-3-2',
        '1-5-4-1',
    ];
}
