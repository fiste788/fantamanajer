<?php

namespace App\Model\Rule;

use App\Model\Table\MatchdaysTable;
use App\Model\Table\TeamsTable;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ModelAwareTrait;

/**
 * @property MatchdaysTable $Matchdays
 * @property TeamsTable $Teams
 */
class LineupExpiredRule
{
    use ModelAwareTrait;

    public function __construct()
    {
        $this->loadModel('Matchdays');
        $this->loadModel('Teams');
    }

    public function __invoke(EntityInterface $entity, array $options)
    {
        $matchday = $this->Matchdays->get($entity->matchday_id);
        $team = $this->Teams->get($entity->team_id, ['contain' => ['Championships']]);

        return $matchday->date->subMinutes($team->championship->minute_lineup)->isFuture();
    }
}
