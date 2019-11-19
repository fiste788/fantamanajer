<?php
declare(strict_types=1);

namespace App\Stream\Verb\Aggregated;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamAggregatedActivity;

class Transfert extends StreamAggregatedActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return string
     */
    public function getBody(): string
    {
        $news = [];
        $olds = [];
        foreach ($this->activity->offsetGet('activities') as $activity) {
            if ($activity->enriched()) {
                $news[] = $activity->offsetGet('object')->new_member->player->full_name;
                $olds[] = $activity->offsetGet('object')->old_member->player->full_name;
            }
        }

        return __('Selled {0} and buyed {1}', [
            implode(', ', $news),
            implode(', ', $olds),
        ]);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return __n('{0} make a transfert', '{0} make {1} transferts', $this->activity->offsetGet('activity_count'), [
            $this->activity->offsetGet('activities')[0]->offsetGet('actor')->name,
            $this->activity->offsetGet('activity_count'),
        ]);
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon(): string
    {
        return 'swap_vert';
    }

    /**
     * Get contain
     *
     * @return array
     */
    public static function contain(): array
    {
        return ['NewMembers.Players', 'OldMembers.Players'];
    }
}
