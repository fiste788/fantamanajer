<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Championship;
use Authorization\IdentityInterface;

class ChampionshipPolicy
{
    /**
     * Can add
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Championship $championship Entity
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Championship $championship)
    {
        return $user->admin;
    }

    /**
     * Can edit
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Championship $championship Entity
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Championship $championship)
    {
        return $user->admin;
    }

    /**
     * Can delete
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Championship $championship Entity
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Championship $championship)
    {
        return $user->admin;
    }

    /**
     * Can index
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Championship $championship Championship
     * @return bool
     */
    public function canIndex(IdentityInterface $user, Championship $championship)
    {
        return $user->admin;
    }
}
