<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Matchday;
use Authorization\IdentityInterface;

class MatchdayPolicy
{
    public function canCurrent(IdentityInterface $user, Matchday $matchday)
    {
        return true;
    }
}
