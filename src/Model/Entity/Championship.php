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
 * @property int $league_id
 * @property League $league
 * @property int $season_id
 * @property Season $season
 * @property Team[] $teams
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

    /**
     *
     * @param User $user
     * @return Team
     */
    public function findTeamByUser($user)
    {
        var_dump($user);
        $teams = TableRegistry::get('Teams');

        return $teams->find()
            ->where(['user_id' => $user, 'championship_id' => $this->id])
            ->first();
    }
}
