<?php

namespace App\Stream\Verb\Aggregated;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamAggregatedActivity;

class Missing extends StreamAggregatedActivity implements StreamActivityInterface
{
    
    public function getBody()
    {
        return null;
    }

    public function getTitle()
    {
        return "Non hai ancora settato la formazione per questa giornata. Sbrigati!";
    }

    public function getIcon()
    {
        return 'star';
    }
}
