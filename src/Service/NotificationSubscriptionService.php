<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Team;
use Cake\ORM\Locator\LocatorAwareTrait;

class NotificationSubscriptionService
{
    use LocatorAwareTrait;

    /**
     * Create default push
     *
     * @param \App\Model\Entity\Team $team Team
     * @return \App\Model\Entity\NotificationSubscription[]
     * @throws \Cake\Core\Exception\CakeException
     */
    public function createDefaultPushSubscription(Team $team): array
    {
        /** @var \App\Model\Table\NotificationSubscriptionsTable $notificationSubscriptionsTable */
        $notificationSubscriptionsTable = $this->fetchTable('NotificationSubscriptions');

        return $notificationSubscriptionsTable->newEntities([
            ['type' => 'push', 'enabled' => true, 'team_id' => $team->id, 'name' => 'lost_member'],
            ['type' => 'push', 'enabled' => true, 'team_id' => $team->id, 'name' => 'score'],
        ]);
    }

    /**
     * Create default email
     *
     * @param \App\Model\Entity\Team $team Team
     * @return \App\Model\Entity\NotificationSubscription[]
     * @throws \Cake\Core\Exception\CakeException
     */
    public function createDefaultEmailSubscription(Team $team): array
    {
        /** @var \App\Model\Table\NotificationSubscriptionsTable $notificationSubscriptionsTable */
        $notificationSubscriptionsTable = $this->fetchTable('NotificationSubscriptions');

        return $notificationSubscriptionsTable->newEntities([
            ['type' => 'email', 'enabled' => true, 'team_id' => $team->id, 'name' => 'score'],
            ['type' => 'email', 'enabled' => true, 'team_id' => $team->id, 'name' => 'lost_member'],
            ['type' => 'email', 'enabled' => false, 'team_id' => $team->id, 'name' => 'lineups'],
        ]);
    }
}
