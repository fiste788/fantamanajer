<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RollOfHonor Entity
 *
 * @property int $team_id
 * @property int $championship_id
 * @property int $league_id
 * @property float|null $points
 * @property int $rank
 *
 * @property \App\Model\Entity\Member $member
 * @property \App\Model\Entity\League $league
 * @property \App\Model\Entity\Championship $championship
 * @property \App\Model\Entity\Team $team
 * @property int $season_id
 */
class RollOfHonor extends Entity
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
    protected array $_accessible = [];
}
