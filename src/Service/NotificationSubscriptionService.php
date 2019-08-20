<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Team;
use Cake\Datasource\ModelAwareTrait;

/**
 *
 * @property \App\Model\Table\NotificationSubscriptionsTable $NotificationSubscriptions
 */
class NotificationSubscriptionService
{
    use ModelAwareTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadModel('NotificationSubscriptions');
    }

    public function createDefaultPushSubscription(Team $team)
    {
        return $this->NotificationSubscriptions->newEntities([
            ['type' => 'push', 'enabled' => true, 'team_id' => $team->id, 'name' => 'lost_member'],
            ['type' => 'push', 'enabled' => true, 'team_id' => $team->id, 'name' => 'score'],
        ]);
    }

    public function createDefaultEmailSubscription(Team $team)
    {
        return $this->NotificationSubscriptions->newEntities([
            ['type' => 'email', 'enabled' => true, 'team_id' => $team->id, 'name' => 'score'],
            ['type' => 'email', 'enabled' => true, 'team_id' => $team->id, 'name' => 'lost_member'],
            ['type' => 'email', 'enabled' => false, 'team_id' => $team->id, 'name' => 'lineups'],
        ]);
    }
}
