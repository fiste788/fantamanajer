<?php
namespace App\Policy;

use App\Model\Entity\Team;
use Authorization\IdentityInterface;

class TeamPolicy
{
    public function canView(IdentityInterface $user, Team $team)
    {
        return true;
    }

    public function canAdd(IdentityInterface $user, Team $team)
    {
        return false;
    }

    public function canEdit(IdentityInterface $user, Team $team)
    {
        return $user->hasTeam($team->id);
    }

    public function canDelete(IdentityInterface $user, Team $team)
    {
        return $user->hasTeam($team->id);
    }

    public function canIndex(IdentityInterface $user, Team $team)
    {
        return $user->isInChampionship($team->championship_id);
    }
}
