<?php
declare(strict_types=1);

namespace App\Stream\Verb\Aggregated;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamAggregatedActivity;

class Post extends StreamAggregatedActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return string|null
     */
    public function getBody(): ?string
    {
        return null;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return __n('{0} posted a conference', '{0} posted {1} conferences', $this->activity['activity_count'], [
            $this->activity['activities'][0]->offsetGet('actor')->name,
            $this->activity['activity_count'],
        ]);
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
