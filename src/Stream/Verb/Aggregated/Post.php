<?php
declare(strict_types=1);

namespace App\Stream\Verb\Aggregated;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamAggregatedActivity;

class Post extends StreamAggregatedActivity implements StreamActivityInterface
{
    public function getBody()
    {
        return null;
    }

    public function getTitle()
    {
        return __n('{0} posted a conference', '{0} posted {1} conferences', $this->activity['activity_count'], [
            $this->activity['activities'][0]->offsetGet('actor')->name,
            $this->activity['activity_count'],
        ]);
    }

    public function getIcon()
    {
        return 'message';
    }
}
