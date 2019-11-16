<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\ArticlesTable;
use Authorization\IdentityInterface;

class ArticlesTablePolicy
{
    /**
     * Scope index
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \Cake\ORM\Query $query query
     * @return \Cake\ORM\Query
     */
    public function scopeIndex($user, $query)
    {
        return $query->where(['Articles.user_id' => $user->getIdentifier()]);
    }

    /**
     * Can add
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Table\ArticlesTable $articleTable Table
     * @return bool
     */
    public function canAdd(IdentityInterface $user, ArticlesTable $articleTable)
    {
        return true;
    }

    /**
     * Can esit
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Table\ArticlesTable $articleTable Table
     * @return bool
     */
    public function canEdit(IdentityInterface $user, ArticlesTable $articleTable)
    {
        return true;
    }
}
