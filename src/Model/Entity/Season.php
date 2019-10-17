<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Season Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $year
 * @property string $key_gazzetta
 * @property bool $bonus_points
 * @property bool $bonus_points_clean_sheet
 * @property \App\Model\Entity\Championship[] $championships
 * @property \App\Model\Entity\Matchday[] $matchdays
 * @property \App\Model\Entity\Member[] $members
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \App\Model\Entity\View0Member[] $view0_members
 * @property \App\Model\Entity\View1MembersStat[] $view1_members_stats
 * @property \App\Model\Entity\View2ClubsStat[] $view2_clubs_stats
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
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'key_gazzetta',
    ];
}
