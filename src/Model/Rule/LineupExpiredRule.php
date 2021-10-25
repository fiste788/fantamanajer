<?php
declare(strict_types=1);

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\Datasource\ModelAwareTrait;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\TeamsTable $Teams
 */
class LineupExpiredRule
{
    use ModelAwareTrait;

    /**
     * Construct
     *
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->loadModel('Matchdays');
        $this->loadModel('Teams');
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
