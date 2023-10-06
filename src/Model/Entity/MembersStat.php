<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MembersStat Entity
 *
 * @property int $member_id
 * @property string|null $sum_present
 * @property string|null $sum_valued
 * @property float|null $avg_points
 * @property float|null $avg_rating
 * @property string|null $sum_goals
 * @property string|null $sum_goals_against
 * @property string|null $sum_assist
 * @property string|null $sum_yellow_card
 * @property string|null $sum_red_card
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
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'members' => false,
    ];
}
