<?php
namespace App\Policy;

use App\Model\Entity\Event;
use Authorization\IdentityInterface;

class EventPolicy
{
    public function canAdd(IdentityInterface $user, Event $event)
    {
        return false;
    }
    
    public function canEdit(IdentityInterface $user, Event $event)
    {
        return false;
    }
    
    public function canDelete(IdentityInterface $user, Event $event)
    {
        return false;
    }
    
    public function canIndex(IdentityInterface $user, Event $event)
    {
        return true;
    }
}