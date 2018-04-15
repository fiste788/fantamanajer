<?php
namespace App\Policy;

use App\Model\Entity\Article;
use Authorization\IdentityInterface;

class ArticlePolicy
{
    public function canEdit(IdentityInterface $user, Article $article)
    {
        return $user->id == $article->user_id;
    }
    
    public function canDelete(IdentityInterface $user, Article $article)
    {
        return $user->id == $article->user_id;
    }
}