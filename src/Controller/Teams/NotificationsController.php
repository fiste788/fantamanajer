<?php
namespace App\Controller\Teams;

use App\Controller\AppController;
use App\Stream\ActivityManager;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;

class NotificationsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $teamId = $this->request->getParam('team_id');
        if (!$this->Authentication->getIdentity()->hasTeam($teamId)) {
            throw new ForbiddenException();
        }
    }

    public function count()
    {
        $teamId = $this->request->getParam('team_id');
        $manager = new \StreamCake\FeedManager();
        $feed = $manager->getFeed('notification', $teamId);
        $stream = $feed->getActivities(0, 20);

        $this->set(
            [
            'success' => true,
            'data' => $stream,
            '_serialize' => ['success', 'data']
            ]
        );
    }

    public function index()
    {
        $teamId = $this->request->getParam('team_id');
        $manager = new ActivityManager();
        $stream = $manager->getActivities('notification', $teamId, true, 0, 20, ['mark_seen' => true]);

        $this->set(
            [
            'success' => true,
            'data' => $stream,
            '_serialize' => ['success', 'data']
            ]
        );
    }
}
