<?php
declare(strict_types=1);

namespace App\Traits;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use Cake\ORM\Locator\LocatorAwareTrait;
use RuntimeException;

/**
 * @property \App\Model\Entity\Matchday $currentMatchday
 * @property \App\Model\Entity\Season $currentSeason
 */
trait CurrentMatchdayTrait
{
    use LocatorAwareTrait;

    /**
     * Current matchday
     *
     * @var \App\Model\Entity\Matchday $currentMatchday
     */
    protected Matchday $currentMatchday;

    /**
     * Current season
     *
     * @var \App\Model\Entity\Season $currentSeason
     */
    protected Season $currentSeason;

    /**
     * Set the current matchday
     *
     * @return void
     * @throws \RuntimeException
     */
    public function getCurrentMatchday(): void
    {
        /** @var \App\Model\Entity\Matchday|null $cur */
        $cur = $this->fetchTable('Matchdays')->find('current')->first();
        if ($cur != null) {
            $this->currentMatchday = $cur;
            $this->currentSeason = $cur->season;
            $this->currentSeason->started = $this->currentMatchday->number > 0;
            $this->currentSeason->ended = $this->currentMatchday->number > 38;
        } else {
            throw new RuntimeException('Cannot find current matchday');
        }
    }
}
