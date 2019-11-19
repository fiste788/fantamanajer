<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Member;
use Authorization\IdentityInterface;

class MemberPolicy
{
    /**
     * Can view
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Member $member Entity
     * @return bool
     */
    public function canView(IdentityInterface $user, Member $member)
    {
        return true;
    }

    /**
     * Can free
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Member $member Entity
     * @return bool
     */
    public function canFree(IdentityInterface $user, Member $member)
    {
        return true;
    }

    /**
     * Can free by role
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Member $member Entity
     * @return bool
     */
    public function canFreeByRole(IdentityInterface $user, Member $member)
    {
        return true;
    }

    /**
     * Can index
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Member $member Entity
     * @return bool
     */
    public function canIndex(IdentityInterface $user, Member $member)
    {
        return true;
    }

    /**
     * Can not mime
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Member $member Entity
     * @return bool
     */
    public function canNotMine(IdentityInterface $user, Member $member)
    {
        return true;
    }
}
