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
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Player $player Player
     * @return bool
     */
    public function canView(IdentityInterface $user, Player $player)
    {
        return true;
    }
}
