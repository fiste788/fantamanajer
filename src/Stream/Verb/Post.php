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
        return $this->activity->offsetGet('actor')->name . ' ha rilasciato una conferenza stampa intitolata ' . $this->activity->offsetGet('object')->title;
    }

    public function getIcon()
    {
        return 'message';
    }
}
