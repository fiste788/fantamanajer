<?php

declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Article;
use Authorization\IdentityInterface;

class ArticlePolicy
{
    /**
     * Can add
     *
     * @param IdentityInterface $user User
     * @param Article $article Entity
     * @return boolean
     */
    public function canAdd(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }

    /**
     * Can edit
     *
     * @param IdentityInterface $user User
     * @param Article $article Entity
     * @return boolean
     */
    public function canEdit(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }

    /**
     * Can delete
     *
     * @param IdentityInterface $user User
     * @param Article $article Entity
     * @return boolean
     */
    public function canDelete(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }

    /**
     * Can index
     *
     * @param IdentityInterface $user User
     * @param Article $article Article
     * @return boolean
     */
    public function canIndex(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }
}
