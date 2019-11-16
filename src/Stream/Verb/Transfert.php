<?php
declare(strict_types=1);

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;

class Transfert extends StreamSingleActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return string|null
     */
    public function getBody(): ?string
    {
        if ($this->activity->enriched()) {
            return __(
                'Selled {0} and buyed {1}',
                $this->activity->offsetGet('object')->old_member->player->full_name,
                $this->activity->offsetGet('object')->new_member->player->full_name
            );
        } else {
            return null;
        }
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return __('{0} make a transfert', $this->activity->offsetGet('actor')->name);
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon(): string
    {
        return 'swap_vert';
    }

    /**
     * Get contain
     *
     * @return array
     */
    public static function contain(): array
    {
        return ['NewMembers.Players', 'OldMembers.Players'];
    }
}
