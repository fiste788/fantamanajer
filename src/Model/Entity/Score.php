<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Score Entity
 *
 * @property int $id
 * @property float $points
 * @property float $real_points
 * @property float $penality_points
 * @property string|null $penality
 * @property int|null $lineup_id
 * @property int $team_id
 * @property int $matchday_id
 *
 * @property \App\Model\Entity\Lineup|null $lineup
 * @property \App\Model\Entity\Team $team
 * @property \App\Model\Entity\Matchday $matchday
 */
class Score extends Entity
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
    protected $_accessible = [
        'points' => true,
        'real_points' => true,
        'penality_points' => true,
        'penality' => true,
        'lineup_id' => false,
        'team_id' => false,
        'matchday_id' => false,
        'lineup' => false,
        'team' => false,
        'matchday' => false,
    ];
}
