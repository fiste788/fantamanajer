<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MembersStat Entity
 *
 * @property int $member_id
 * @property int|null $sum_present
 * @property int|null $sum_valued
 * @property float|null $avg_points
 * @property float|null $avg_rating
 * @property int|null $sum_goals
 * @property int|null $sum_goals_against
 * @property int|null $sum_assist
 * @property int|null $sum_yellow_card
 * @property int|null $sum_red_card
 * @property int $quotation
 *
 * @property \App\Model\Entity\Member $member
 */
class MembersStat extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var bool[]
     */
    protected $_accessible = [
        'members' => false,
    ];
}
