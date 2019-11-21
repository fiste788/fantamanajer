<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Player;
use Authorization\IdentityInterface;

class PlayerPolicy
{
    /**
     * Can view
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Player $player Player
     *
     * @return true
     */
    public function canView(IdentityInterface $user, Player $player): bool
    {
        return true;
    }
}
