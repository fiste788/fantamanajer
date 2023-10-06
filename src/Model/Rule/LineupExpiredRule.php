<?php
declare(strict_types=1);

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Locator\LocatorAwareTrait;

class LineupExpiredRule
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
        if (isset($options['admin']) && $options['admin']) {
            return true;
        } else {
            /** @var \App\Model\Entity\Matchday $matchday */
            $matchday = $this->fetchTable('Matchdays')->get($entity->matchday_id);
            /** @var \App\Model\Entity\Team $team */
            $team = $this->fetchTable('Teams')->get($entity->team_id, 'all', null, null, contain: ['Championships']);

            return $matchday->date->subMinutes($team->championship->minute_lineup)->isFuture();
        }
    }
}
