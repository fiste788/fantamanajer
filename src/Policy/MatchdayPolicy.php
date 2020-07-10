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
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Matchday $matchday Entity
     * @return true
     */
    public function canCurrent(IdentityInterface $user, Matchday $matchday): bool
    {
        return true;
    }
}
