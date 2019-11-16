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
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Club $club Entity
     * @return bool
     */
    public function canIndex(IdentityInterface $user, Club $club)
    {
        return true;
    }

    /**
     * Can view
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Club $club Club
     * @return bool
     */
    public function canView(IdentityInterface $user, Club $club)
    {
        return true;
    }
}
