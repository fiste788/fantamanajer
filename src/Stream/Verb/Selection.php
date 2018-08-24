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
        return $this->activity->offsetGet('actor')->name . " ha selezionato un giocatore per l'acquisto";
    }

    public function getIcon()
    {
        return 'gavel';
    }
}
