<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\AppController;
use App\Stream\ActivityManager;
use Cake\Event\EventInterface;
use StreamCake\FeedManager;

/**
 * @property \Cake\ORM\Table $Notifications
 */
class NotificationsController extends AppController
{
    /**
     * {@inheritDoc}
     *
     * @throws \Authorization\Exception\ForbiddenException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        //$teamId = (int)$this->request->getParam('team_id');
        //** @var \App\Model\Entity\User|null $identity */
        //$identity = $this->Authentication->getIdentity();
        //if (!$identity?->hasTeam($teamId)) {
        //    throw new ForbiddenException();
        //}
    }

    /**
     * Count
     *
     * @return void
     * @throws \GetStream\Stream\StreamFeedException
     */
    public function count(): void
    {
        $teamId = (string)$this->request->getParam('team_id');
        $manager = new FeedManager();
        $feed = $manager->getFeed('notification', $teamId);
        $stream = $feed->getActivities(0, 20);

        $this->set(
            [
                'success' => true,
                'data' => $stream,
            ],
        );

        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }

    /**
     * Index
     *
     * @return void
     * @throws \GetStream\Stream\StreamFeedException
     */
    public function index(): void
    {
        $teamId = (string)$this->request->getParam('team_id');
        $manager = new ActivityManager();
        $stream = $manager->getActivities('notification', $teamId, true, 0, 20, ['mark_seen' => true]);

        $this->set(
            [
                'success' => true,
                'data' => $stream,
            ],
        );

        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }
}
