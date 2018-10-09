<?php
namespace App\Policy;

use App\Model\Entity\Championship;
use Authorization\IdentityInterface;

class ChampionshipPolicy
{
    public function canAdd(IdentityInterface $user, Championship $championship)
    {
        return $user->admin;
    }

    public function canEdit(IdentityInterface $user, Championship $championship)
    {
        return $user->admin;
    }

    public function canDelete(IdentityInterface $user, Championship $championship)
    {
        return $user->admin;
    }

    public function canIndex(IdentityInterface $user, Championship $championship)
    {
        return $user->admin;
    }
}
