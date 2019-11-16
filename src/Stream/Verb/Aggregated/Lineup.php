<?php
declare(strict_types=1);

namespace App\Stream\Verb\Aggregated;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamAggregatedActivity;

class Lineup extends StreamAggregatedActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return string
     */
    public function getBody(): string
    {
        $lineup = $this->activity['activities'][0]->offsetGet('object');
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
        return __('{0} has setup lineup for matchday {1}', [
            $this->activity['activities'][0]->offsetGet('actor')->name,
            $this->activity['activities'][0]->offsetGet('object')->matchday->number,
        ]);
    }

    /**
     * Get icon
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
     * @return array
     */
    public static function contain(): array
    {
        return ['Matchdays', 'Dispositions.Members.Players'];
    }
}
