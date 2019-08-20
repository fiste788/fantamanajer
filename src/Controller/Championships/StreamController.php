<?php
declare(strict_types=1);

namespace App\Controller\Championships;

use App\Controller\AppController;
use App\Stream\ActivityManager;
use Cake\Http\Exception\ForbiddenException;

/**
 * Steam Controller
 */
class StreamController extends AppController
{
    public function index()
    {
        $championshipId = $this->request->getParam('championship_id');
        if (!$this->Authentication->getIdentity()->isInChampionship($championshipId)) {
            throw new ForbiddenException();
        }

        $page = (int)$this->request->getQuery('page', 1);
        $rowsForPage = 10;
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('championship', $championshipId, true, $offset, $rowsForPage);
        $this->set([
            'stream' => $stream,
            '_serialize' => 'stream',
        ]);
    }
}
