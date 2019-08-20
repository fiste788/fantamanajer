<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Club;
use Authorization\IdentityInterface;

class ClubPolicy
{
    public function canIndex(IdentityInterface $user, Club $club)
    {
        return true;
    }

    public function canView(IdentityInterface $user, Club $club)
    {
        return true;
    }
}
