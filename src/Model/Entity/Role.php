<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Role Entity.
 *
 * @property int $id
 * @property string $singolar
 * @property string $plural
 * @property string $abbreviation
 * @property string $determinant
 * @property \App\Model\Entity\Member[] $members
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \App\Model\Entity\View0Member[] $view0_members
 * @property \App\Model\Entity\View1MembersStat[] $view1_members_stats
 */
class Role extends Entity
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
