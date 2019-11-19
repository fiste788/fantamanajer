<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VwMembersStat Entity.
 *
 * @property int $member_id
 * @property \App\Model\Entity\Member $member
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
 */
class VwMembersStat extends Entity
{
}
