<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Score Entity.
 *
 * @property int $id
 * @property float $points
 * @property float $real_points
 * @property float $penality_points
 * @property null|string $penality
 * @property int $team_id
 * @property \App\Model\Entity\Team $team
 * @property int $matchday_id
 * @property \App\Model\Entity\Matchday $matchday
 * @property int $lineup_id
 * @property \App\Model\Entity\Lineup $lineup
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
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
