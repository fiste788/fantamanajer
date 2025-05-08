<?php
declare(strict_types=1);

namespace App\Stream\Verb\Aggregated;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamAggregatedActivity;
use Override;

class Selection extends StreamAggregatedActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return null
     */
    #[Override]
    public function getBody(): ?string
    {
        return null;
    }

    /**
     * Get title
     *
     * @return string
     */
    #[Override]
    public function getTitle(): string
    {
        return __n(
            'A player has been selected for transfert',
            '{0} players have been selected for transfert',
            (int)($this->activity->offsetGet('activity_count') ?? 0),
            (int)($this->activity->offsetGet('activity_count') ?? 0),
        );
    }

    /**
     * Get icon
     *
     * @return string
     */
    #[Override]
    public function getIcon(): string
    {
        return 'gavel';
    }
}
