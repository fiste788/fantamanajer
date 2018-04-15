<?php
namespace App\Policy;

use App\Model\Table\ArticlesTable;
use Authorization\IdentityInterface;

class ArticlesTablePolicy
{
    public function scopeIndex($user, $query)
    {
        return $query->where(['Articles.user_id' => $user->getIdentifier()]);
    }
    
    public function canAdd(IdentityInterface $user, ArticlesTable $articleTable) {
        return true;
    }
    
    public function canEdit(IdentityInterface $user, ArticlesTable $articleTable) {
        return true;
    }
}
