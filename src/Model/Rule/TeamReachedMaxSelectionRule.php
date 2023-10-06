<?php
declare(strict_types=1);

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query\SelectQuery;

class TeamReachedMaxSelectionRule
{
    use LocatorAwareTrait;

    /**
     * Invoke
     *
     * @param \App\Model\Entity\Selection $entity Entity
     * @param array<string, mixed> $options Options
     * @return bool
     * @throws \Cake\Core\Exception\CakeException
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        /** @var \App\Model\Table\ChampionshipsTable $championshipsTable */
        $championshipsTable = $this->fetchTable('Championships');
        /** @var \App\Model\Entity\Championship $championship */
        $championship = $championshipsTable->find()->innerJoinWith(
            'Teams',
            function (SelectQuery $q) use ($entity): SelectQuery {
                return $q->where(['Teams.id' => $entity->team_id]);
            }
        )->first();

        /** @var \App\Model\Table\SelectionsTable $selectionsTable */
        $selectionsTable = $this->fetchTable('Selections');
        /** @var \App\Model\Entity\Selection $lastSelection */
        $lastSelection = $selectionsTable->find()->where([
            'matchday_id' => $entity->matchday_id,
            'team_id' => $entity->team_id,
            'processed' => false,
        ])->all()->last();

        $count = $selectionsTable->find()->distinct('new_member_id')->where([
            'matchday_id' => $entity->matchday_id,
            'team_id' => $entity->team_id,
            'processed' => false,
        ])->count();

        return $count < $championship->number_selections || $lastSelection->new_member_id == $entity->new_member_id;
    }
}
