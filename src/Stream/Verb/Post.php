<?php

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;

class Post extends StreamSingleActivity implements StreamActivityInterface
{

    public function getBody()
    {
        return $this->activity->offsetGet('object')->body;
    }

    public function getTitle()
    {
        return __('{0} posted a conference', $this->activity->offsetGet('actor')->name);
    }

    public function getIcon()
    {
        return 'message';
    }
}
