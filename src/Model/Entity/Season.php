<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Season Entity
 *
 * @property int $id
 * @property string $name
 * @property int $year
 * @property string $key_gazzetta
 * @property bool $bonus_points_goals
 * @property bool $bonus_points_clean_sheet
 * @property bool $started
 * @property bool $ended
 *
 * @property array<\App\Model\Entity\Championship> $championships
 * @property array<\App\Model\Entity\Matchday> $matchdays
 * @property array<\App\Model\Entity\Member> $members
 */
class Season extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'year' => true,
        'key_gazzetta' => false,
        'bonus_points_goals' => true,
        'bonus_points_clean_sheet' => true,
        'championships' => false,
        'matchdays' => false,
        'members' => false,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var list<string>
     */
    protected array $_hidden = [
        'key_gazzetta',
        'bonus_points_goals',
        'bonus_points_clean_sheet',
    ];

    /**
     * Undocumented variable
     *
     * @var list<string>
     */
    protected array $_virtual = [
        'started',
        'ended',
    ];
}
