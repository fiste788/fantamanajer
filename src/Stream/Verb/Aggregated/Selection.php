<?php

declare(strict_types=1);

namespace App\Stream\Verb\Aggregated;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamAggregatedActivity;

class Selection extends StreamAggregatedActivity implements StreamActivityInterface
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
        return __n(
            'A player has been selected for the transfert',
            '{0} players have been selected for the transfert',
            $this->activity->offsetGet('activity_count') ?? 0,
            $this->activity->offsetGet('activity_count') ?? 0
        );
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon(): string
    {
        return 'gavel';
    }
}
