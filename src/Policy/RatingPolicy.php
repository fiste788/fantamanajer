<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Rating;
use Authorization\IdentityInterface;

class RatingPolicy
{
    /**
     * Can index
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Rating $rating Entity
     *
     * @return true
     */
    public function canIndex(IdentityInterface $user, Rating $rating): bool
    {
        return true;
    }
}
