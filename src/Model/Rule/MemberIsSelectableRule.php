<?php
declare(strict_types=1);

namespace App\Model\Rule;

use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;

/**
 * @property \App\Model\Table\SelectionsTable $Selections
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Service\SelectionService $Selection
 */
class MemberIsSelectableRule
{
    use ServiceAwareTrait;
    use LocatorAwareTrait;

    /**
     * Construct
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->fetchTable('Selections');
        $this->fetchTable('Scores');
        $this->loadService('Selection');
    }

    /**
     * Invoke
     *
     * @param \App\Model\Entity\Selection $entity Entity
     * @param array $options Options
     * @return bool
     * @throws \ErrorException
     * @throws \Cake\Mailer\Exception\MissingMailerException
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     * @throws \Cake\Mailer\Exception\MissingActionException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \BadMethodCallException
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        $selection = $this->Selections->findAlreadySelectedMember($entity);
        if ($selection != null) {
            $ranking = $this->Scores->find('ranking', [
                'championship_id' => $selection->team->championship_id,
            ]);

            /** @var array $rank */
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
