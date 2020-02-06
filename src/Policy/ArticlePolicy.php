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
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Article $article Entity
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }

    /**
     * Can edit
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Article $article Entity
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }

    /**
     * Can view
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Article $article Entity
     * @return bool
     */
    public function canView(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }

    /**
     * Can delete
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Article $article Entity
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }

    /**
     * Can index
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\Article $article Article
     * @return bool
     */
    public function canIndex(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }
}
