<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Lineup;
use Authorization\IdentityInterface;

class LineupPolicy
{
    /**
     * Can current
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @return true
     */
    public function canCurrent(IdentityInterface $user, Lineup $lineup): bool
    {
        return true;
    }

    /**
     * Can add
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Lineup $lineup): bool
    {
        return $user->hasTeam($lineup->team_id);
    }

    /**
     * Can edit
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Lineup $lineup): bool
    {
        return $user->hasTeam($lineup->team_id);
    }

    /**
     * Can delete
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Lineup $lineup): bool
    {
        return $user->hasTeam($lineup->team_id);
    }
}
