<?php
namespace App\Policy;

use App\Model\Entity\PushSubscription;
use Authorization\IdentityInterface;

class PushSubscriptionPolicy
{
    public function canEdit(IdentityInterface $user, PushSubscription $subscription)
    {
        return $user->id == $subscription->user_id;
    }
    
    public function canDelete(IdentityInterface $user, PushSubscription $subscription)
    {
        return $user->id == $subscription->user_id;
    }
    
    public function canAdd(IdentityInterface $user, PushSubscription $subscription)
    {
        return $user->id == $subscription->user_id;
    }
    
}