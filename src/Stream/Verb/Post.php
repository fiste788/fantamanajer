<?php
declare(strict_types=1);

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;
use Override;

class Post extends StreamSingleActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return string
     */
    #[Override]
    public function getBody(): string
    {
        /** @var \App\Model\Entity\Article $article */
        $article = $this->activity->offsetGet('object');

        return $article->body;
    }

    /**
     * Get title
     *
     * @return string
     */
    #[Override]
    public function getTitle(): string
    {
        /** @var \App\Model\Entity\Team $team */
        $team = $this->activity->offsetGet('actor');

        return __('{0} posted a conference', $team->name);
    }

    /**
     * Get icon
     *
     * @return string
     */
    #[Override]
    public function getIcon(): string
    {
        return 'message';
    }
}
