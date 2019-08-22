<?php

namespace App\Stream\Verb\Aggregated;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamAggregatedActivity;
use Cake\Utility\Text;

class Transfert extends StreamAggregatedActivity implements StreamActivityInterface
{

    public function getBody()
    {
        $news = [];
        $olds = [];
        foreach ($this->activity['activities'] as $activity) {
            if ($activity->enriched()) {
                $news[] = $activity->offsetGet('object')->new_member->player->full_name;
                $olds[] = $activity->offsetGet('object')->old_member->player->full_name;
            }
        }

        return __('Selled {0} and buyed {1}', [
            implode(', ', $news),
            implode(', ', $olds)
        ]);
    }

    public function getTitle()
    {
        return __n('{0} make a transfert', '{0} make {1} transferts', $this->activity['activity_count'], [
            $this->activity['activities'][0]->offsetGet('actor')->name,
            $this->activity['activity_count']
        ]);
    }

    public function getIcon()
    {
        return 'swap_vert';
    }

    public static function contain()
    {
        return ['NewMembers.Players', 'OldMembers.Players'];
    }
}
