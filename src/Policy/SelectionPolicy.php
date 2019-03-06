<?php
namespace App\Policy;

use App\Model\Entity\Selection;
use Authorization\IdentityInterface;

class SelectionPolicy
{
    public function canAdd(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->team_id);
    }

    public function canEdit(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->team_id);
    }

    public function canDelete(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->team_id);
    }

    public function canIndex(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->team_id);
    }
}
