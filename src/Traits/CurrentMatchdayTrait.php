<?php

namespace App\Traits;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use Cake\ORM\TableRegistry;

trait CurrentMatchdayTrait
{
    /**
     *
     * @var Matchday
     */
    protected $currentMatchday;

    /**
     *
     * @var Season
     */
    protected $currentSeason;

    public function initialize()
    {
        $this->getCurrentMatchday();
    }

    public function getCurrentMatchday()
    {
        $matchdays = TableRegistry::get("Matchdays");
        $this->currentMatchday = $matchdays->find('current')->first();
        if ($this->currentMatchday != null) {
            $this->currentSeason = $this->currentMatchday->season;
        }
    }
}
