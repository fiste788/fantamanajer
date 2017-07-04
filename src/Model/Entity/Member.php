<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Member Entity.
 *
 * @property int $id
 * @property int $code_gazzetta
 * @property int $active
 * @property int $player_id
 * @property \App\Model\Entity\Player $player
 * @property int $role_id
 * @property \App\Model\Entity\Role $role
 * @property int $club_id
 * @property \App\Model\Entity\Club $club
 * @property int $season_id
 * @property \App\Model\Entity\Season $season
 * @property \App\Model\Entity\Disposition[] $dispositions
 * @property \App\Model\Entity\Rating[] $ratings
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \App\Model\Entity\View0MembersOnlyStat[] $view0_members_only_stats
 * @property \App\Model\Entity\Team[] $teams
 */
class Member extends Entity
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
