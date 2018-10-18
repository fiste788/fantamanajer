<?php
namespace App\Policy;

use App\Model\Entity\Score;
use Authorization\IdentityInterface;

class ScorePolicy
{
    public function canView(IdentityInterface $user, Score $score)
    {
        return true;
    }

    public function canLast(IdentityInterface $user, Score $score)
    {
        return true;
    }

    public function canIndex(\App\Model\Entity\User $user, Score $score)
    {
        return $user->isInChampionship($score->team->championship_id);
    }
    
    public function canEdit(\App\Model\Entity\User $user, Score $score)
    {
        return $user->admin || $user->isChampionshipAdmin($score->team->championship_id);
    }
}
