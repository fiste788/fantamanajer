<?php
declare(strict_types=1);

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\TeamsTable $Teams
 */
class LineupExpiredRule
{
    use LocatorAwareTrait;

    /**
     * Construct
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->fetchTable('Matchdays');
        $this->fetchTable('Teams');
    }

    /**
     * Invoke
     *
     * @param \App\Model\Entity\Lineup $entity Entity
     * @param array $options Options
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        if($options['admin']) {
            return true;
        }
        $matchday = $this->Matchdays->get($entity->matchday_id);
        $team = $this->Teams->get($entity->team_id, ['contain' => ['Championships']]);

        return $matchday->date->subMinutes($team->championship->minute_lineup)->isFuture();
    }
}
