<?php

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;

class Missing extends StreamSingleActivity implements StreamActivityInterface
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
