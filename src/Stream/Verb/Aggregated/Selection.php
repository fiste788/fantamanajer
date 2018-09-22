<?php

namespace App\Stream\Verb\Aggregated;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamAggregatedActivity;

class Selection extends StreamAggregatedActivity implements StreamActivityInterface
{

    public function getBody()
    {
        return null;
    }

    public function getTitle()
    {
        return __n('A player has been selected for the transfert', '{0} players have been selected for the transfert', $this->activity['activity_count'],$this->activity['activity_count']);
    }

    public function getIcon()
    {
        return 'gavel';
    }
}
