<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\PushSubscription;
use Authorization\IdentityInterface;

class PushSubscriptionPolicy
{
    /**
     * Can edit
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\PushSubscription $subscription Entity
     * @return bool
     */
    public function canEdit(IdentityInterface $user, PushSubscription $subscription): bool
    {
        return $user->id == $subscription->user_id;
    }

    /**
     * Can delete
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\PushSubscription $subscription Entity
     * @return bool
     */
    public function canDelete(IdentityInterface $user, PushSubscription $subscription): bool
    {
        return $user->id == $subscription->user_id;
    }

    /**
     * Can add
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\PushSubscription $subscription Entity
     * @return bool
     */
    public function canAdd(IdentityInterface $user, PushSubscription $subscription): bool
    {
        return $user->id == $subscription->user_id;
    }
}
