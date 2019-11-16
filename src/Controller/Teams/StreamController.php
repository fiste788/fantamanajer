<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\AppController;
use App\Stream\ActivityManager;

/**
 * Steam Controller
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
        $teamId = $this->request->getParam('team_id');
        /*if (!$this->Authentication->getIdentity()->hasTeam($teamId)) {
            throw new ForbiddenException();
        }*/

        $page = (int)$this->request->getQuery('page', 1);
        $rowsForPage = 10;
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('team', $teamId, false, $offset, $rowsForPage);
        $this->set([
            'success' => true,
            'data' => $stream,
            '_serialize' => ['success', 'data'],
        ]);
    }
}
