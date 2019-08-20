<?php
declare(strict_types=1);

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
        return __('Your lineup for current matchday is missing. Come on!');
    }

    public function getIcon()
    {
        return 'star';
    }
}
