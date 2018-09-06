<?php

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
        return 'Sono state rilasciate ' . $this->activity['activity_count'] . ' conferenze stampa';
    }

    public function getIcon()
    {
        return 'message';
    }
}
