<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VwMembersStat Entity.
 *
 * @property int $member_id
 * @property \App\Model\Entity\Member $member
 * @property float $sum_present
 * @property float $sum_valued
 * @property float $avg_points
 * @property float $avg_rating
 * @property float $sum_goals
 * @property float $sum_goals_against
 * @property float $sum_assist
 * @property float $sum_yellow_card
 * @property float $sum_red_card
 * @property int $quotation
 */
class VwMembersStat extends Entity
{
}
