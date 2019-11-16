<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Selection;
use Authorization\IdentityInterface;

class SelectionPolicy
{
    /**
     * Can add
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Selection $selection Entity
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->team_id);
    }

    /**
     * Can edit
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Selection $selection Entity
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->team_id);
    }

    /**
     * Can delete
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Selection $selection Entity
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->team_id);
    }

    /**
     * Can index
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Selection $selection Entity
     * @return bool
     */
    public function canIndex(IdentityInterface $user, Selection $selection)
    {
        return $user->hasTeam($selection->team_id);
    }
}
