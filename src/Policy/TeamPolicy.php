<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Team;
use Authorization\IdentityInterface;

class TeamPolicy
{
    /**
     * Can view
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Team $team Entity
     * @return bool
     */
    public function canView(IdentityInterface $user, Team $team)
    {
        return $user->isInChampionship($team->championship_id);
    }

    /**
     * Can add
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Team $team Entity
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Team $team)
    {
        return $user->admin || ($user->isChampionshipAdmin($team->championship_id));
    }

    /**
     * Can edit
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Team $team Entity
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Team $team)
    {
        return $user->hasTeam($team->id) || $user->admin;
    }

    /**
     * Can delete
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Team $team Entity
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Team $team)
    {
        return false;
    }

    /**
     * Can index
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Team $team Entity
     * @return bool
     */
    public function canIndex(IdentityInterface $user, Team $team)
    {
        return $user->isInChampionship($team->championship_id);
    }
}
