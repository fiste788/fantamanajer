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
            /** @var \App\Model\Entity\Transfert $transfert */
            $transfert = $this->activity->offsetGet('object');

            return __(
                'Selled {0} and buyed {1}',
                $transfert->old_member->player->full_name,
                $transfert->new_member->player->full_name
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
        /** @var \App\Model\Entity\Team $team */
        $team = $this->activity->offsetGet('actor');

        return __('{0} make a transfert', $team->name);
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
     * @return array<string>
     */
    public static function contain(): array
    {
        return ['NewMembers.Players', 'OldMembers.Players'];
    }
}
