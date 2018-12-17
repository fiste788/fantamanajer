<?php

namespace App\Model\Rule;

use App\Model\Table\MatchdaysTable;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ModelAwareTrait;

/**
 * @property MatchdaysTable $Matchdays
 */
class JollyAlreadyUsedRule
{

    use ModelAwareTrait;

    public function __construct()
    {
        $this->loadModel('Matchdays');
    }

    public function __invoke(EntityInterface $entity, array $options)
    {
        if ($entity->jolly) {
            $matchday = $this->Matchdays->get($entity->matchday_id);
            $matchdays = $this->Matchdays->find()
                ->where(['season_id' => $matchday->season_id])
                ->count();

            return $this->find()
                    ->contain(['Matchdays'])
                    ->innerJoinWith('Matchdays')
                    ->where([
                        'Lineups.id IS NOT' => $entity->id,
                        'jolly' => true,
                        'team_id' => $entity->team_id,
                        'Matchdays.number ' . ($matchday->number <= $matchdays / 2 ? '<=' : '>') => $matchdays / 2
                    ])
                    ->isEmpty();
        }

        return true;
    }
}
