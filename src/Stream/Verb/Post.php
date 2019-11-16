<?php
declare(strict_types=1);

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;

class Post extends StreamSingleActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->activity->offsetGet('object')->body;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return __('{0} posted a conference', $this->activity->offsetGet('actor')->name);
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon(): string
    {
        return 'message';
    }
}
