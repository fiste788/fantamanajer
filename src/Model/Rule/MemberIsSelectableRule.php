<?php

namespace App\Model\Rule;

use App\Model\Table\SelectionsTable;
use App\Service\SelectionService;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ModelAwareTrait;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * @property SelectionsTable $Selections
 * @property ScoresTable $Scores
 * @property SelectionService $Selection
 */
class MemberIsSelectableRule
{
    use ServiceAwareTrait;
    use ModelAwareTrait;

    public function __construct()
    {
        $this->loadModel('Selections');
        $this->loadModel('Scores');
        $this->loadService('Selection');
    }
    
    public function __invoke(EntityInterface $entity, array $options)
    {
        $selection = $this->Selections->findAlreadySelectedMember($entity);
        if ($selection != null) {
            $ranking = $this->Scores->find('ranking', [
                'championship_id' => $selection->team->championship_id
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
