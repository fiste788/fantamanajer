<?php
declare(strict_types=1);

namespace App\Traits;

use Cake\Datasource\ModelAwareTrait;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Entity\Matchday $currentMatchday
 * @property \App\Model\Entity\Season $currentSeason
 */
trait CurrentMatchdayTrait
{
    use ModelAwareTrait;

    /**
     * Current matchday
     *
     * @var \App\Model\Entity\Matchday $currentMatchday
     */
    protected $currentMatchday;

    /**
     * Current season
     *
     * @var \App\Model\Entity\Season $currentSeason
     */
    protected $currentSeason;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        $this->loadModel('Matchdays');
        $this->getCurrentMatchday();
    }

    /**
     * Set the current matchday
     *
     * @return void
     */
    public function getCurrentMatchday(): void
    {
        /** @var \App\Model\Entity\Matchday|null $cur */
        $cur = $this->Matchdays->find('current')->first();
        if ($cur != null) {
            $this->currentMatchday = $cur;
            $this->currentSeason = $cur->season;
        }
    }
}
