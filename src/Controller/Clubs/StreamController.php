<?php
declare(strict_types=1);

namespace App\Controller\Clubs;

use App\Controller\AppController;
use App\Stream\ActivityManager;
use Cake\Utility\Hash;

/**
 * Steam Controller
 *
 * @property \Cake\ORM\Table $Stream
 */
class StreamController extends AppController
{
    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        $clubId = $this->request->getParam('club_id');

        $page = (int)Hash::get($this->request->getQueryParams(), 'page', 1);
        $rowsForPage = 10;
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('club', $clubId, false, (int)$offset, $rowsForPage);
        $this->set([
            'stream' => $stream,
            '_serialize' => 'stream',
        ]);
    }
}
