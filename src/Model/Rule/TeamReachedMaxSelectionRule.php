<?php

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\Datasource\ModelAwareTrait;

/**
 * @property \App\Model\Table\SelectionsTable $Selections
 * @property \App\Model\Table\ChampionshipsTable $Championships
 */
class TeamReachedMaxSelectionRule
{

    use ModelAwareTrait;

    public function __construct()
    {
        $this->loadModel('Selections');
        $this->loadModel('Championships');
    }

    public function __invoke(EntityInterface $entity, array $options)
    {
        $championship = $this->Championships->find()->innerJoinWith(
                'Teams',
                function ($q) use ($entity) {
                return $q->where(['Teams.id' => $entity->team_id]);
            }
            )->first();

        return $this->Selections->find()->where([
                'matchday_id' => $entity->matchday_id,
                'team_id' => $entity->team_id,
                'processed' => false
            ])->count() < $championship->number_selections;
    }
}
