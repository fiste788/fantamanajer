<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Championship Entity
 *
 * @property int $id
 * @property bool $captain
 * @property int $number_transferts
 * @property int $number_selections
 * @property int $minute_lineup
 * @property int $points_missed_lineup
 * @property bool $captain_missed_lineup
 * @property bool $started
 * @property bool|null $jolly
 * @property int $league_id
 * @property int $season_id
 *
 * @property \App\Model\Entity\League $league
 * @property \App\Model\Entity\Season $season
 * @property \App\Model\Entity\Team[] $teams
 */
class Championship extends Entity
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
        'captain' => true,
        'number_transferts' => true,
        'number_selections' => true,
        'minute_lineup' => true,
        'points_missed_lineup' => true,
        'captain_missed_lineup' => true,
        'started' => false,
        'jolly' => true,
        'league_id' => true,
        'season_id' => true,
        'league' => true,
        'season' => true,
        'teams' => false,
    ];
}
