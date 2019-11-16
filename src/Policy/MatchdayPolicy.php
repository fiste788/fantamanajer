<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Matchday;
use Authorization\IdentityInterface;

class MatchdayPolicy
{
    /**
     * Can current
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Matchday $matchday Entity
     * @return bool
     */
    public function canCurrent(IdentityInterface $user, Matchday $matchday)
    {
        return true;
    }
}
