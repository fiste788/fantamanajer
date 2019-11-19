<?php
declare(strict_types=1);

namespace App\Traits;

use Cake\ORM\TableRegistry;

/**
 * @property \App\Model\Entity\Matchday $currentMatchday
 * @property \App\Model\Entity\Season $currentSeason
 *
 */
trait CurrentMatchdayTrait
{
    protected $currentMatchday;

    protected $currentSeason;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        $this->getCurrentMatchday();
    }

    /**
     * Set the current matchday
     *
     * @return void
     */
    public function getCurrentMatchday(): void
    {
        $matchdays = TableRegistry::getTableLocator()->get("Matchdays");

        /** @var \App\Model\Entity\Matchday $cur */
        $cur = $matchdays->find('current')->first();
        if ($cur != null) {
            $this->currentMatchday = $cur;
            $this->currentSeason = $cur->season;
        }
    }
}
