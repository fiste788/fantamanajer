<?php
declare(strict_types=1);

namespace App\Traits;

use Cake\ORM\TableRegistry;

trait CurrentMatchdayTrait
{
    /**
     *
     * @var \App\Model\Entity\Matchday
     */
    protected $currentMatchday;

    /**
     *
     * @var \App\Model\Entity\Season
     */
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
        $this->currentMatchday = $matchdays->find('current')->first();
        if ($this->currentMatchday != null) {
            $this->currentSeason = $this->currentMatchday->season;
        }
    }
}
