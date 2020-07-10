<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Club;
use Authorization\IdentityInterface;

class ClubPolicy
{
    /**
     * Can index
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Club $club Entity
     * @return true
     */
    public function canIndex(IdentityInterface $user, Club $club): bool
    {
        return true;
    }

    /**
     * Can view
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Club $club Club
     * @return true
     */
    public function canView(IdentityInterface $user, Club $club): bool
    {
        return true;
    }
}
