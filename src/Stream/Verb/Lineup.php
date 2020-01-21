<?php
declare(strict_types=1);

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;

class Lineup extends StreamSingleActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return string
     */
    public function getBody(): string
    {
        /** @var \App\Model\Entity\Lineup $lineup */
        $lineup = $this->activity->offsetGet('object');

        /** @var \App\Model\Entity\Disposition[] $regular */
        $regular = array_splice($lineup->dispositions, 0, 11);
        $players = [];
        foreach ($regular as $disposition) {
            $players[] = $disposition->member->player->surname;
        }

        return implode(', ', $players);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        /** @var \App\Model\Entity\Lineup $lineup */
        $lineup = $this->activity->offsetGet('object');

        /** @var \App\Model\Entity\Team $team */
        $team = $this->activity->offsetGet('actor');

        return __('{0} has setup lineup for matchday {1}', [
            $team->name,
            $lineup->matchday->number,
        ]);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getIcon(): string
    {
        return 'star';
    }

    /**
     * Get contain
     *
     * @return string[]
     *
     * @psalm-return array{0: string, 1: string}
     */
    public static function contain(): array
    {
        return ['Matchdays', 'Dispositions.Members.Players'];
    }
}
