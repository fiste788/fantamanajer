<?php
declare(strict_types=1);

namespace App\Model\Rule;

use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ModelAwareTrait;
use Cake\Utility\Hash;

/**
 * @property \App\Model\Table\SelectionsTable $Selections
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Service\SelectionService $Selection
 */
class MemberIsSelectableRule
{
    use ServiceAwareTrait;
    use ModelAwareTrait;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->loadModel('Selections');
        $this->loadModel('Scores');
        $this->loadService('Selection');
    }

    /**
     * Invoke
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity
     * @param array $options Options
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        $selection = $this->Selections->findAlreadySelectedMember($entity);
        if ($selection != null) {
            $ranking = $this->Scores->find('ranking', [
                'championship_id' => $selection->team->championship_id,
            ]);
            $rank = Hash::extract($ranking->toArray(), '{n}.team_id');
            if (array_search($entity->team_id, $rank) > array_search($selection->team->id, $rank)) {
                $selection->active = false;
                $this->Selections->save($selection);
                $this->Selection->notifyLostMember($selection);

                return true;
            } else {
                return false;
            }
        }

        return true;
    }
}
