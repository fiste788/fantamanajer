<?php
declare(strict_types=1);

namespace App\Stream\Verb\Aggregated;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamAggregatedActivity;
use Override;

class Transfert extends StreamAggregatedActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return string
     */
    #[Override]
    public function getBody(): string
    {
        $news = [];
        $olds = [];

        /** @var array<\StreamCake\EnrichedActivity> $activities */
        $activities = $this->activity->offsetGet('activities') ?? [];
        foreach ($activities as $activity) {
            if ($activity->enriched()) {
                /** @var \App\Model\Entity\Transfert $transfert */
                $transfert = $activity->offsetGet('object');
                $news[] = $transfert->new_member->player->full_name;
                $olds[] = $transfert->old_member->player->full_name;
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
    #[Override]
    public function getTitle(): string
    {
        /** @var array<\StreamCake\EnrichedActivity> $activities */
        $activities = $this->activity->offsetGet('activities');

        /** @var \App\Model\Entity\Team $team */
        $team = $activities[0]->offsetGet('actor');

        return __n(
            '{0} make a transfert',
            '{0} make {1} transferts',
            (int)($this->activity->offsetGet('activity_count') ?? 0),
            [
                $team->name,
                (int)($this->activity->offsetGet('activity_count') ?? 0),
            ],
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
        return 'swap_vert';
    }

    /**
     * Get contain
     *
     * @return array<string>
     */
    #[Override]
    public static function contain(): array
    {
        return ['NewMembers.Players', 'OldMembers.Players'];
    }
}
