<?php
declare(strict_types=1);

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\Datasource\ModelAwareTrait;
use Cake\ORM\Query;

/**
 * @property \App\Model\Table\SelectionsTable $Selections
 * @property \App\Model\Table\ChampionshipsTable $Championships
 */
class TeamReachedMaxSelectionRule
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
        $this->loadModel('Selections');
        $this->loadModel('Championships');
    }

    /**
     * Invoke
     *
     * @param \App\Model\Entity\Selection $entity Entity
     * @param array $options Options
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        /** @var \App\Model\Entity\Championship $championship */
        $championship = $this->Championships->find()->innerJoinWith(
            'Teams',
            function (Query $q) use ($entity): Query {
                return $q->where(['Teams.id' => $entity->team_id]);
            }
        )->first();

        /** @var \App\Model\Entity\Selection $lastSelection */
        $lastSelection = $this->Selections->find()->where([
            'matchday_id' => $entity->matchday_id,
            'team_id' => $entity->team_id,
            'processed' => false,
        ])->last();

        $count = $this->Selections->find()->distinct('new_member_id')->where([
            'matchday_id' => $entity->matchday_id,
            'team_id' => $entity->team_id,
            'processed' => false,
        ])->count();
        
        return $count < $championship->number_selections || $lastSelection->new_member_id == $entity->new_member_id;
    }
}
