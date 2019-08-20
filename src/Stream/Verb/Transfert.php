<?php
declare(strict_types=1);

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;

class Transfert extends StreamSingleActivity implements StreamActivityInterface
{
    public function getBody()
    {
        if ($this->activity->enriched()) {
            return __(
                'Selled {0} and buyed {1}',
                $this->activity->offsetGet('object')->old_member->player->full_name,
                $this->activity->offsetGet('object')->new_member->player->full_name
            );
        }
    }

    public function getTitle()
    {
        return __('{0} make a transfert', $this->activity->offsetGet('actor')->name);
    }

    public function getIcon()
    {
        return 'swap_vert';
    }

    public static function contain()
    {
        return ['NewMembers.Players', 'OldMembers.Players'];
    }
}
