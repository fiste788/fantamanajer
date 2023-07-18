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
     * @return null
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
        /** @var array<\StreamCake\EnrichedActivity> $activities */
        $activities = $this->activity->offsetGet('activities');

        /** @var \App\Model\Entity\Team $team */
        $team = $activities[0]->offsetGet('actor');

        return __n(
            '{0} posted a conference',
            '{0} posted {1} conferences',
            (int)($this->activity->offsetGet('activity_count') ?? 0),
            [
                $team->name,
                (int)($this->activity->offsetGet('activity_count') ?? 0),
            ]
        );
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
