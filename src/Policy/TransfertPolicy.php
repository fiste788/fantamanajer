<?php
namespace App\Policy;

use App\Model\Entity\Transfert;
use Authorization\IdentityInterface;

class TransfertPolicy
{
    public function canAdd(IdentityInterface $user, Transfert $transfert)
    {
        return $user->hasTeam($transfert->team_id);
    }
    
    public function canEdit(IdentityInterface $user, Transfert $transfert)
    {
        return false;
    }
    
    public function canDelete(IdentityInterface $user, Transfert $transfert)
    {
        return false;
    }
    
    public function canIndex(IdentityInterface $user, Transfert $transfert)
    {
        return true;
    }
}