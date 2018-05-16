<?php
namespace App\Policy;

use App\Model\Entity\Selection;
use Authorization\IdentityInterface;

class SelectionPolicy
{
    public function canAdd(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->id);
    }

    public function canEdit(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->id);
    }

    public function canDelete(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->id);
    }
}
