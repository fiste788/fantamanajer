<?php
namespace App\Policy;

use App\Model\Entity\Member;
use Authorization\IdentityInterface;

class MemberPolicy
{
    public function canView(IdentityInterface $user, Member $member)
    {
        return true;
    }
    
    public function canFree(IdentityInterface $user, Member $member)
    {
        return true;
    }
    
    public function canIndex(IdentityInterface $user, Member $member)
    {
        return true;
    }
}