<?php
namespace App\Controller\Clubs;

use App\Controller\AppController;
use App\Stream\ActivityManager;

/**
 * Steam Controller
 */
class StreamController extends AppController
{
    
    public function index()
    {
        $clubId = $this->request->getParam('club_id');
        $manager = new ActivityManager();
        $stream = $manager->getActivities('club', $clubId, false);
        $this->set([
            'stream' => $stream,
            '_serialize' => 'stream'
        ]);
    }
}
