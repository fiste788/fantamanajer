<?php
namespace App\Policy;

use App\Model\Entity\Lineup;
use Authorization\IdentityInterface;

class LineupPolicy
{
    public function canCurrent(IdentityInterface $user, Lineup $lineup)
    {
        return true;
    }
    
    public function canAdd(IdentityInterface $user, Lineup $lineup)
    {
        return $user->hasTeam($lineup->team_id);
    }
    
    public function canEdit(IdentityInterface $user, Lineup $lineup)
    {
        return $user->hasTeam($lineup->team_id);
    }
    
    public function canDelete(IdentityInterface $user, Lineup $lineup)
    {
        return $user->hasTeam($lineup->team_id);
    }
}