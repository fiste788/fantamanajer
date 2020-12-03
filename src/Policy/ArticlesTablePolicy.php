<?php

declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\ArticlesTable;
use Authorization\IdentityInterface;
use Cake\ORM\Query;

class ArticlesTablePolicy
{
    /**
     * Scope index
     *
     * @param \App\Model\Entity\User $user User
     * @param \Cake\ORM\Query $query query
     *
     * @return Query
     */
    public function scopeIndex(IdentityInterface $user, Query $query): Query
    {
        return $query->where(['Articles.user_id' => $user->getIdentifier()]);
    }

    /**
     * Can add
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Table\ArticlesTable $articleTable Table
     * @return true
     */
    public function canAdd(IdentityInterface $user, ArticlesTable $articleTable): bool
    {
        return true;
    }

    /**
     * Can esit
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Table\ArticlesTable $articleTable Table
     * @return true
     */
    public function canEdit(IdentityInterface $user, ArticlesTable $articleTable): bool
    {
        return true;
    }
}
