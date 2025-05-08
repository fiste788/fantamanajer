<?php
declare(strict_types=1);

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;
use Override;

class Selection extends StreamSingleActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return null
     */
    #[Override]
    public function getBody(): ?string
    {
        return null;
    }

    /**
     * Get title
     *
     * @return string
     */
    #[Override]
    public function getTitle(): string
    {
        /** @var \App\Model\Entity\Team $team */
        $team = $this->activity->offsetGet('actor');

        return __('{0} has selected a player for the transfert', $team->name);
    }

    /**
     * Get icon
     *
     * @return string
     */
    #[Override]
    public function getIcon(): string
    {
        return 'gavel';
    }
}
