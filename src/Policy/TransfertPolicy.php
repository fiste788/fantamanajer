<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Transfert;
use Authorization\IdentityInterface;

class TransfertPolicy
{
    /**
     * Can add
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Transfert $transfert Entity
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Transfert $transfert)
    {
        return $user->hasTeam($transfert->team_id);
    }

    /**
     * Can edit
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Transfert $transfert Entity
     * @return false
     */
    public function canEdit(IdentityInterface $user, Transfert $transfert): bool
    {
        return false;
    }

    /**
     * Can delete
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Transfert $transfert Entity
     * @return false
     */
    public function canDelete(IdentityInterface $user, Transfert $transfert): bool
    {
        return false;
    }

    /**
     * Can index
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Transfert $transfert Entity
     * @return true
     */
    public function canIndex(IdentityInterface $user, Transfert $transfert): bool
    {
        return true;
    }
}
