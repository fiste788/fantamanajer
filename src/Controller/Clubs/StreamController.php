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
        
        $page = $this->request->getQuery('page', 1);
        $rowsForPage = 10;
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('club', $clubId, false, $offset, $rowsForPage);
        $this->set([
            'stream' => $stream,
            '_serialize' => 'stream'
        ]);
    }
}
