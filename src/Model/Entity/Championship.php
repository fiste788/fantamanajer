<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Championship Entity.
 *
 * @property int $id
 * @property bool $captain
 * @property int $number_transferts
 * @property int $number_selections
 * @property int $minute_lineup
 * @property int $points_missed_lineup
 * @property bool $captain_missed_lineup
 * @property bool $jolly
 * @property bool $started
 * @property int $league_id
 * @property \App\Model\Entity\League $league
 * @property int $season_id
 * @property \App\Model\Entity\Season $season
 * @property \App\Model\Entity\Team[] $teams
 * @property \App\Model\Entity\View0MaxPoint[] $view0_max_points
 * @property \App\Model\Entity\View2TeamsStat[] $view2_teams_stats
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
        '*' => true,
        'id' => false,
    ];
}
