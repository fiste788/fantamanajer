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
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int|null $captain_id
 * @property int|null $vcaptain_id
 * @property int|null $vvcaptain_id
 * @property int $matchday_id
 * @property int $team_id
 *
 * @property \App\Model\Entity\Member|null $captain
 * @property \App\Model\Entity\Member|null $vcaptain
 * @property \App\Model\Entity\Member|null $vvcaptain
 * @property \App\Model\Entity\Matchday $matchday
 * @property \App\Model\Entity\Team $team
 * @property \App\Model\Entity\Disposition[] $dispositions
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
        'module' => true,
        'jolly' => true,
        'cloned' => true,
        'created_at' => false,
        'modified_at' => false,
        'captain_id' => true,
        'vcaptain_id' => true,
        'vvcaptain_id' => true,
        'matchday_id' => true,
        'team_id' => true,
        'captain' => true,
        'vcaptain' => true,
        'vvcaptain' => true,
        'matchday' => true,
        'team' => true,
        'dispositions' => true,
        'score' => false,
    ];

    /**
     * Fields with the possible modules of a lineup
     *
     * @var string[]
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
