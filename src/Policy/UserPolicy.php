<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Authorization\IdentityInterface;

class UserPolicy
{
    /**
     * Can edit
     *
     * @param \Authorization\IdentityInterface $identity User
     * @param \App\Model\Entity\User $user Entity
     * @return bool
     */
    public function canEdit(IdentityInterface $identity, User $user)
    {
        return $identity->id == $user->id;
    }

    /**
     * Can delete
     *
     * @param \Authorization\IdentityInterface $identity User
     * @param \App\Model\Entity\User $user Entity
     * @return bool
     */
    public function canDelete(IdentityInterface $identity, User $user)
    {
        return false;
    }

    /**
     * Can add
     *
     * @param \Authorization\IdentityInterface $identity User
     * @param \App\Model\Entity\User $user Entity
     * @return bool
     */
    public function canAdd(IdentityInterface $identity, User $user)
    {
        return true;
    }
}
