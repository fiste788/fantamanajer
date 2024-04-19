<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Team;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use GetStream\Stream\Client;

/**
 * @property \App\Service\NotificationSubscriptionService $NotificationSubscription
 * @property \App\Service\ScoreService $Score
 */
#[\AllowDynamicProperties]
class TeamService
{
    use LocatorAwareTrait;
    use ServiceAwareTrait;

    /**
     * Constructor
     *
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->loadService('Score');
        $this->loadService('NotificationSubscription');
    }


    /**
     * Create new team
     *
     * @param \App\Model\Entity\Team $team The team to create
     * @return bool
     * @throws \Cake\Core\Exception\CakeException
     * @throws \GetStream\Stream\StreamFeedException
     */
    public function createTeam(Team $team): bool
    {
        $team->scores = $this->Score->createMissingPoints($team);
        $team->push_notification_subscriptions = $this->NotificationSubscription->createDefaultPushSubscription($team);
        $team->email_notification_subscriptions = $this->NotificationSubscription
            ->createDefaultEmailSubscription($team);
        if ($this->fetchTable('Teams')->save($team, ['associated' => true])) {
            /** @var array<string, string> $config */
            $config = Configure::read('GetStream.default');
            $client = new Client($config['appKey'], $config['appSecret']);
            $championshipFeed = $client->feed('championship', (string)$team->championship_id);
            $teamFeed = $client->feed('team', (string)$team->id);
            $userFeed = $client->feed('user', (string)$team->user_id);
            $userFeed->follow($teamFeed->getSlug(), $teamFeed->getUserId());
            $championshipFeed->follow($teamFeed->getSlug(), $teamFeed->getUserId());

            return true;
        } else {
            return false;
        }
    }
}
