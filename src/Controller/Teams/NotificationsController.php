<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\AppController;
use App\Stream\ActivityManager;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;

/**
 * @property \Cake\ORM\Table $Notifications
 */
class NotificationsController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $teamId = (int)$this->request->getParam('team_id');
        /** @var \App\Model\Entity\User $identity */
        $identity = $this->Authentication->getIdentity();
        if (!$identity->hasTeam($teamId)) {
            throw new ForbiddenException();
        }
    }

    /**
     * Count
     *
     * @return void
     */
    public function count()
    {
        $teamId = (string)$this->request->getParam('team_id');
        $manager = new \StreamCake\FeedManager();
        $feed = $manager->getFeed('notification', $teamId);
        $stream = $feed->getActivities(0, 20);

        $this->set(
            [
                'success' => true,
                'data' => $stream,
                '_serialize' => ['success', 'data'],
            ]
        );
    }

    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        $teamId = (string)$this->request->getParam('team_id');
        $manager = new ActivityManager();
        $stream = $manager->getActivities('notification', $teamId, true, 0, 20, ['mark_seen' => true]);

        $this->set(
            [
                'success' => true,
                'data' => $stream,
                '_serialize' => ['success', 'data'],
            ]
        );
    }
}
