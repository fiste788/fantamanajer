<?php
namespace App\Policy;

use App\Model\Entity\Article;
use Authorization\IdentityInterface;

class ArticlePolicy
{
    public function canAdd(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }

    public function canEdit(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }

    public function canDelete(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }

    public function canIndex(IdentityInterface $user, Article $article)
    {
        return $user->hasTeam($article->team_id);
    }
}
