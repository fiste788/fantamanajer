<?php

namespace App\Service;

use App\Model\Entity\Team;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use GetStream\Stream\Client;

/**
 * @property \App\Service\NotificationSubscriptionService $NotificationSubscription
 * @property \App\Service\ScoreService $Score
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamService
{
    use ModelAwareTrait;
    use ServiceAwareTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadModel("Teams");
        $this->loadService("Score");
        $this->loadService("NotificationSubscription");
    }

    /**
     * Create new team
     *
     * @param Team $team The team to create
     * @return bool
     */
    public function createTeam(Team $team)
    {
        $team->scores = $this->Score->createMissingPoints($team);
        $team->push_notification_subscriptions = $this->NotificationSubscription->createDefaultPushSubscription($team);
        $team->email_notification_subscriptions = $this->NotificationSubscription->createDefaultEmailSubscription($team);
        if ($this->Teams->save($team, ['associated' => true])) {
            $config = Configure::read('GetStream.default');
            $client = new Client($config['appKey'], $config['appSecret']);
            $championshipFeed = $client->feed('championship', $team->championship_id);
            $teamFeed = $client->feed('team', $team->id);
            $userFeed = $client->feed('user', $team->user_id);
            $userFeed->follow($teamFeed->getSlug(), $teamFeed->getUserId());
            $championshipFeed->follow($teamFeed->getSlug(), $teamFeed->getUserId());

            return true;
        } else {
            return false;
        }
    }
}
