<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Team;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * @property \App\Model\Table\NotificationSubscriptionsTable $NotificationSubscriptions
 */
class NotificationSubscriptionService
{
    use LocatorAwareTrait;

    /**
     * Constructor
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->NotificationSubscriptions = $this->fetchTable('NotificationSubscriptions');
    }

    /**
     * Create default push
     *
     * @param \App\Model\Entity\Team $team Team
     * @return \App\Model\Entity\NotificationSubscription[]
     */
    public function createDefaultPushSubscription(Team $team): array
    {
        return $this->NotificationSubscriptions->newEntities([
            ['type' => 'push', 'enabled' => true, 'team_id' => $team->id, 'name' => 'lost_member'],
            ['type' => 'push', 'enabled' => true, 'team_id' => $team->id, 'name' => 'score'],
        ]);
    }

    /**
     * Create default email
     *
     * @param \App\Model\Entity\Team $team Team
     * @return \App\Model\Entity\NotificationSubscription[]
     */
    public function createDefaultEmailSubscription(Team $team): array
    {
        return $this->NotificationSubscriptions->newEntities([
            ['type' => 'email', 'enabled' => true, 'team_id' => $team->id, 'name' => 'score'],
            ['type' => 'email', 'enabled' => true, 'team_id' => $team->id, 'name' => 'lost_member'],
            ['type' => 'email', 'enabled' => false, 'team_id' => $team->id, 'name' => 'lineups'],
        ]);
    }
}
