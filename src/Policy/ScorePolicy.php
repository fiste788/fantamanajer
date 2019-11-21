<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Score;
use App\Model\Entity\User;
use Authorization\IdentityInterface;

class ScorePolicy
{
    /**
     * Can view
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Score $score Entity
     *
     * @return true
     */
    public function canView(IdentityInterface $user, Score $score): bool
    {
        return true;
    }

    /**
     * Can last
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Score $score Entity
     *
     * @return true
     */
    public function canLast(IdentityInterface $user, Score $score): bool
    {
        return true;
    }

    /**
     * Can index
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Score $score Entity
     * @return bool
     */
    public function canIndex(User $user, Score $score)
    {
        return $user->isInChampionship($score->team->championship_id);
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Score $score Entity
     * @return bool
     */
    public function canEdit(User $user, Score $score)
    {
        return $user->admin || $user->isChampionshipAdmin($score->team->championship_id);
    }
}
