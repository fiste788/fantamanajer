<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Player;
use Authorization\IdentityInterface;

class PlayerPolicy
{
    public function canView(IdentityInterface $user, Player $player)
    {
        return true;
    }
}
