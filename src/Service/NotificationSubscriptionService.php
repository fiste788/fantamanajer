<?php

namespace App\Service;

use App\Model\Entity\Team;

class NotificationSubscriptionService
{
    public function createDefaultPushSubscription(Team $team)
    {
        return $this->newEntities([
            ['type' => 'push', 'enabled' => true, 'team_id' => $team->id, 'name' => 'lost_member'],
            ['type' => 'push', 'enabled' => true, 'team_id' => $team->id, 'name' => 'score']
        ]);
    }

    public function createDefaultEmailSubscription(Team $team)
    {
        return $this->newEntities([
            ['type' => 'email', 'enabled' => true, 'team_id' => $team->id, 'name' => 'score'],
            ['type' => 'email', 'enabled' => true, 'team_id' => $team->id, 'name' => 'lost_member'],
            ['type' => 'email', 'enabled' => false, 'team_id' => $team->id, 'name' => 'lineups']
        ]);
    }
}
