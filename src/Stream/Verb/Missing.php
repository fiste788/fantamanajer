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
        return "Non hai ancora settato la formazione per questa giornata. Sbrigati!";
    }

    public function getIcon()
    {
        return 'star';
    }
}
