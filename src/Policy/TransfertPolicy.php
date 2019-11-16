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
     * @param \Authorization\IdentityInterface $user User
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
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Transfert $transfert Entity
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Transfert $transfert)
    {
        return false;
    }

    /**
     * Can delete
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Transfert $transfert Entity
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Transfert $transfert)
    {
        return false;
    }

    /**
     * Can index
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Transfert $transfert Entity
     * @return bool
     */
    public function canIndex(IdentityInterface $user, Transfert $transfert)
    {
        return true;
    }
}
