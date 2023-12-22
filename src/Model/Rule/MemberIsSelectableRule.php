<?php
declare(strict_types=1);

namespace App\Model\Rule;

use App\Service\SelectionService;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use League\Container\ContainerAwareTrait;

class MemberIsSelectableRule
{
    use LocatorAwareTrait;
    use ContainerAwareTrait;

    /**
     * Invoke
     *
     * @param \App\Model\Entity\Selection $entity Entity
     * @param array<string, mixed> $options Options
     * @return bool
     * @throws \ErrorException
     * @throws \Cake\Core\Exception\CakeException
     * @throws \Cake\Mailer\Exception\MissingMailerException
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     * @throws \Cake\Mailer\Exception\MissingActionException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \BadMethodCallException
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        /** @var \App\Model\Table\SelectionsTable $selectionsTable */
        $selectionsTable = $this->fetchTable('Selections');
        $selection = $selectionsTable->findAlreadySelectedMember($entity);
        if ($selection != null) {
            $ranking = $this->fetchTable('Scores')->find('ranking', championship_id: $selection->team->championship_id);

            /** @var array<int> $rank */
            $rank = Hash::extract($ranking->toArray(), '{n}.team_id');
            if (array_search($entity->team_id, $rank) > array_search($selection->team->id, $rank)) {
                $selection->active = false;
                $selectionsTable->save($selection);
                $this->getContainer()->get(SelectionService::class)->notifyLostMember($selection);

                return true;
            } else {
                return false;
            }
        }

        return true;
    }
}
