<?php
declare(strict_types=1);

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Locator\LocatorAwareTrait;

class JollyAlreadyUsedRule
{
    use LocatorAwareTrait;

    /**
     * Invoke
     *
     * @param \App\Model\Entity\Lineup $entity Entity
     * @param array<string, mixed> $options Options
     * @return bool
     * @throws \Cake\Core\Exception\CakeException
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        if ($entity->jolly) {
            /** @var \App\Model\Table\MatchdaysTable $matchdaysTable  */
            $matchdaysTable = $this->fetchTable('Matchdays');
            $matchday = $matchdaysTable->get($entity->matchday_id);
            $matchdays = $matchdaysTable->find()
                ->where(['season_id' => $matchday->season_id])
                ->count();

            $lineupsTable = $this->fetchTable('Lineups');

            return $lineupsTable->find()
                ->contain(['Matchdays'])
                ->innerJoinWith('Matchdays')
                ->where([
                    'Lineups.id IS NOT' => $entity->id,
                    'jolly' => true,
                    'team_id' => $entity->team_id,
                    'Matchdays.number ' . ($matchday->number <= $matchdays / 2 ? '<=' : '>') => $matchdays / 2,
                ])
                ->all()->isEmpty();
        }

        return true;
    }
}
