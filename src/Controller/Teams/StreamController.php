<?php
declare(strict_types=1);

namespace App\Controller\Teams;

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
     * @throws \InvalidArgumentException
     * @throws \GetStream\Stream\StreamFeedException
     */
    public function index(): void
    {
        $teamId = (string)$this->request->getParam('team_id');
        /*if (!$this->Authentication->getIdentity()->hasTeam($teamId)) {
            throw new ForbiddenException();
        }*/

        $page = (int)Hash::get($this->request->getQueryParams(), 'page', 1);
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
