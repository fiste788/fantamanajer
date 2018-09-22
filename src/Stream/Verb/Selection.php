<?php

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;

class Selection extends StreamSingleActivity implements StreamActivityInterface
{

    public function getBody()
    {
        return null;
    }

    public function getTitle()
    {
        return __('{0} has selected a player for the transfert', $this->activity->offsetGet('actor')->name);
    }

    public function getIcon()
    {
        return 'gavel';
    }
}
