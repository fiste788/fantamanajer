<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Championship Entity
 *
 * @property int $id
 * @property bool $started
 * @property bool $captain
 * @property bool|null $jolly
 * @property bool $captain_missed_lineup
 * @property bool $bonus_points_goals
 * @property bool $bonus_points_clean_sheet
 * @property int $minute_lineup
 * @property int $points_missed_lineup
 * @property int $number_substitutions
 * @property int $number_benchwarmers
 * @property int $number_transferts
 * @property int $number_selections
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
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'started' => false,
        'captain' => true,
        'jolly' => true,
        'captain_missed_lineup' => true,
        'bonus_points_goals' => true,
        'bonus_points_clean_sheet' => true,
        'minute_lineup' => true,
        'points_missed_lineup' => true,
        'number_substitutions' => true,
        'number_benchwarmers' => true,
        'number_transferts' => true,
        'number_selections' => true,
        'league_id' => false,
        'season_id' => false,
        'league' => false,
        'season' => false,
        'teams' => false,
    ];
}
